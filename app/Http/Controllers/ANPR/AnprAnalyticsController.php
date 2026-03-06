<?php

namespace App\Http\Controllers\ANPR;

use App\Http\Controllers\Controller;
use App\Models\ANPR\Gate;
use App\Models\ANPR\Record;
use App\Models\Vehicle\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnprAnalyticsController extends Controller
{
    /**
     * Display the analytics page.
     */
    public function index(Request $request)
    {
        $stats = [
            'total_scans_today' => rand(200, 400),
            'total_scans_week' => rand(1500, 2500),
            'avg_daily_scans' => rand(180, 300),
            'peak_hour' => '8:00 AM - 9:00 AM',
        ];

        $hourlyData = $this->getHourlyData();
        $gateDistribution = $this->getGateDistribution();
        $vehicleTypes = $this->getVehicleTypeDistribution();

        return view('anpr.analytics.index', compact('stats', 'hourlyData', 'gateDistribution', 'vehicleTypes'));
    }

    /**
     * Download a generated report.
     */
    public function downloadReport(Request $request)
    {
        $params = session('anpr_report_params');

        if (!$params) {
            return redirect()->route('anpr.analytics')->with('error', 'No report parameters found. Please generate a report again.');
        }

        // Clear the session data
        session()->forget('anpr_report_params');

        $startDate = Carbon::parse($params['start_date'])->startOfDay();
        $endDate = Carbon::parse($params['end_date'])->endOfDay();
        $type = $params['type'];
        $format = $params['format'];
        $gateFilter = $params['gate_filter'] ?? 'all';
        $locationFilter = $params['location_filter'] ?? 'all';

        // Build query
        $query = Record::query()
            ->with(['gate', 'vehicle.user.details'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($gateFilter !== 'all', fn($q) => $q->byGateName($gateFilter))
            ->when($locationFilter !== 'all', fn($q) => $q->byGateLocation($locationFilter))
            ->orderBy('detected_at', 'desc');

        if ($type === 'flagged') {
            $query->where('is_flagged', true);
        }

        $records = $query->get();

        // Cross-reference plates with registered vehicles
        $plateNumbers = $records->pluck('plate_number')->unique()->toArray();
        $registeredVehicles = Vehicle::with('user.details')
            ->whereIn('plate_number', $plateNumbers)
            ->get()
            ->keyBy('plate_number');

        if ($format === 'csv' || $format === 'excel') {
            return $this->streamCsvReport($records, $registeredVehicles, $type, $startDate, $endDate, $gateFilter, $locationFilter, $format);
        }

        return $this->streamPdfReport($records, $registeredVehicles, $type, $startDate, $endDate, $gateFilter, $locationFilter);
    }

    /**
     * Stream a CSV/Excel report download.
     */
    protected function streamCsvReport($records, $registeredVehicles, string $type, $startDate, $endDate, string $gateFilter, string $locationFilter, string $format)
    {
        $extension = $format === 'excel' ? 'xls' : 'csv';
        $contentType = $format === 'excel' ? 'application/vnd.ms-excel' : 'text/csv';
        $filename = "anpr_{$type}_report_{$startDate->format('Ymd')}_{$endDate->format('Ymd')}.{$extension}";

        return response()->streamDownload(function () use ($records, $registeredVehicles, $type, $startDate, $endDate, $gateFilter, $locationFilter) {
            $handle = fopen('php://output', 'w');

            // BOM for Excel UTF-8 compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Report header
            fputcsv($handle, ['ANPR ' . ucfirst($type) . ' Report']);
            fputcsv($handle, ['Generated', now()->format('M d, Y H:i:s')]);
            fputcsv($handle, ['Date Range', $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y')]);
            fputcsv($handle, ['Gate Filter', $gateFilter !== 'all' ? $gateFilter : 'All Gates']);
            fputcsv($handle, ['Direction Filter', $locationFilter !== 'all' ? $locationFilter : 'All Directions']);
            fputcsv($handle, ['Total Records', $records->count()]);
            fputcsv($handle, []);

            if ($type === 'summary') {
                $this->writeSummaryCsv($handle, $records, $registeredVehicles);
            } else {
                $this->writeDetailedCsv($handle, $records, $registeredVehicles);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => $contentType,
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Write summary CSV content.
     */
    protected function writeSummaryCsv($handle, $records, $registeredVehicles): void
    {
        fputcsv($handle, ['=== OVERALL STATISTICS ===']);
        fputcsv($handle, ['Metric', 'Value']);
        fputcsv($handle, ['Total Detections', $records->count()]);
        fputcsv($handle, ['Unique Plates', $records->pluck('plate_number')->unique()->count()]);
        fputcsv($handle, ['Flagged Records', $records->filter(fn($r) => $r->is_flagged)->count()]);
        fputcsv($handle, ['Average Confidence', round($records->avg('confidence') * 100, 1) . '%']);
        fputcsv($handle, ['Registered Vehicles Detected', $registeredVehicles->count()]);
        fputcsv($handle, []);

        fputcsv($handle, ['=== GATE BREAKDOWN ===']);
        fputcsv($handle, ['Gate', 'Direction', 'Count']);
        $gateGroups = $records->groupBy(fn($r) => ($r->gate?->gate_name ?? 'Unknown') . '|' . ($r->gate?->gate_location ?? 'Unknown'));
        foreach ($gateGroups as $key => $group) {
            [$gateName, $gateLocation] = explode('|', $key);
            fputcsv($handle, [$gateName, $gateLocation, $group->count()]);
        }
        fputcsv($handle, []);

        fputcsv($handle, ['=== TOP PLATES WITH OWNER INFO ===']);
        fputcsv($handle, ['Plate Number', 'Detection Count', 'Gate Pass', 'Vehicle Owner', 'Vehicle Info', 'Gate Pass Status']);
        $plateCounts = $records->groupBy('plate_number')
            ->map(fn($group) => $group->count())
            ->sortDesc()
            ->take(50);

        foreach ($plateCounts as $plate => $count) {
            $vehicle = $registeredVehicles->get($plate);
            $ownerName = $vehicle?->user?->details?->full_name ?? 'Unregistered';
            $gatePass = $vehicle?->assigned_gate_pass ?? 'None';
            $vehicleInfo = $vehicle ? $vehicle->vehicle_info : 'N/A';
            $gatePassStatus = $vehicle ? ($vehicle->isExpired() ? 'Expired' : ($vehicle->isExpiringSoon() ? 'Expiring Soon' : 'Active')) : 'N/A';

            fputcsv($handle, [$plate, $count, $gatePass, $ownerName, $vehicleInfo, $gatePassStatus]);
        }
    }

    /**
     * Write detailed CSV content.
     */
    protected function writeDetailedCsv($handle, $records, $registeredVehicles): void
    {
        fputcsv($handle, [
            'Detection Time', 'Plate Number', 'Confidence', 'Gate', 'Direction',
            'Status', 'Camera', 'Gate Pass', 'Vehicle Owner', 'Vehicle Info',
            'Owner Gate Pass', 'Gate Pass Status',
        ]);

        foreach ($records as $record) {
            $vehicle = $registeredVehicles->get($record->plate_number);
            $ownerName = $vehicle?->user?->details?->full_name ?? '';
            $gatePass = $vehicle?->assigned_gate_pass ?? '';
            $vehicleInfo = $vehicle ? $vehicle->vehicle_info : '';
            $gatePassStatus = $vehicle ? ($vehicle->isExpired() ? 'Expired' : ($vehicle->isExpiringSoon() ? 'Expiring Soon' : 'Active')) : '';

            fputcsv($handle, [
                $record->detected_at?->format('Y-m-d H:i:s') ?? $record->created_at->format('Y-m-d H:i:s'),
                $record->plate_number,
                round($record->confidence * 100, 1) . '%',
                $record->gate?->gate_name ?? 'Unknown',
                $record->gate?->gate_location ?? 'Unknown',
                $record->is_flagged ? 'Flagged' : 'Normal',
                $record->camera_id ?? 'N/A',
                $record->gate_pass_number ?? 'None',
                $ownerName,
                $vehicleInfo,
                $gatePass,
                $gatePassStatus,
            ]);
        }
    }

    /**
     * Stream a PDF (HTML) report download.
     */
    protected function streamPdfReport($records, $registeredVehicles, string $type, $startDate, $endDate, string $gateFilter, string $locationFilter)
    {
        $filename = "anpr_{$type}_report_{$startDate->format('Ymd')}_{$endDate->format('Ymd')}.html";

        return response()->streamDownload(function () use ($records, $registeredVehicles, $type, $startDate, $endDate, $gateFilter, $locationFilter) {
            $totalDetections = $records->count();
            $uniquePlates = $records->pluck('plate_number')->unique()->count();
            $flaggedCount = $records->filter(fn($r) => $r->is_flagged)->count();
            $avgConfidence = round($records->avg('confidence') * 100, 1);
            $registeredCount = $registeredVehicles->count();
            $reportTitle = ucfirst($type) . ' Report';

            echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>ANPR ' . $reportTitle . '</title>';
            echo '<style>
                body { font-family: Arial, sans-serif; margin: 40px; color: #333; }
                h1 { color: #1a5632; border-bottom: 3px solid #1a5632; padding-bottom: 10px; }
                h2 { color: #1a5632; margin-top: 30px; }
                .meta { color: #666; margin-bottom: 20px; }
                .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin: 20px 0; }
                .stat-card { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 15px; text-align: center; }
                .stat-value { font-size: 28px; font-weight: bold; color: #1a5632; }
                .stat-label { font-size: 13px; color: #666; margin-top: 5px; }
                table { width: 100%; border-collapse: collapse; margin: 15px 0; font-size: 13px; }
                th { background: #1a5632; color: white; padding: 10px 8px; text-align: left; }
                td { padding: 8px; border-bottom: 1px solid #dee2e6; }
                tr:nth-child(even) { background: #f8f9fa; }
                .badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; }
                .badge-flagged { background: #fee2e2; color: #dc2626; }
                .badge-normal { background: #dcfce7; color: #16a34a; }
                .badge-active { background: #dcfce7; color: #16a34a; }
                .badge-expired { background: #fee2e2; color: #dc2626; }
                .badge-expiring { background: #fef3c7; color: #d97706; }
                .badge-unreg { background: #f3f4f6; color: #6b7280; }
                .footer { margin-top: 40px; padding-top: 15px; border-top: 1px solid #dee2e6; color: #999; font-size: 12px; }
                @media print { body { margin: 20px; } }
            </style></head><body>';

            echo '<h1>ANPR ' . $reportTitle . '</h1>';
            echo '<div class="meta">';
            echo '<strong>Date Range:</strong> ' . $startDate->format('M d, Y') . ' &mdash; ' . $endDate->format('M d, Y') . '<br>';
            echo '<strong>Gate:</strong> ' . ($gateFilter !== 'all' ? e($gateFilter) : 'All Gates') . ' | ';
            echo '<strong>Direction:</strong> ' . ($locationFilter !== 'all' ? e($locationFilter) : 'All Directions') . '<br>';
            echo '<strong>Generated:</strong> ' . now()->format('M d, Y H:i:s') . '</div>';

            // Stats cards
            echo '<div class="stats-grid">';
            echo '<div class="stat-card"><div class="stat-value">' . number_format($totalDetections) . '</div><div class="stat-label">Total Detections</div></div>';
            echo '<div class="stat-card"><div class="stat-value">' . number_format($uniquePlates) . '</div><div class="stat-label">Unique Plates</div></div>';
            echo '<div class="stat-card"><div class="stat-value">' . number_format($flaggedCount) . '</div><div class="stat-label">Flagged</div></div>';
            echo '<div class="stat-card"><div class="stat-value">' . $avgConfidence . '%</div><div class="stat-label">Avg Confidence</div></div>';
            echo '<div class="stat-card"><div class="stat-value">' . number_format($registeredCount) . '</div><div class="stat-label">Registered Vehicles</div></div>';
            echo '</div>';

            if ($type === 'summary') {
                // Gate breakdown
                echo '<h2>Gate Breakdown</h2><table><thead><tr><th>Gate</th><th>Direction</th><th>Count</th></tr></thead><tbody>';
                $gateGroups = $records->groupBy(fn($r) => ($r->gate?->gate_name ?? 'Unknown') . '|' . ($r->gate?->gate_location ?? 'Unknown'));
                foreach ($gateGroups as $key => $group) {
                    [$gateName, $gateLocation] = explode('|', $key);
                    echo '<tr><td>' . e($gateName) . '</td><td>' . e($gateLocation) . '</td><td>' . $group->count() . '</td></tr>';
                }
                echo '</tbody></table>';

                // Top plates with owner info
                echo '<h2>Top Detected Plates with Owner Information</h2>';
                echo '<table><thead><tr><th>#</th><th>Plate</th><th>Detections</th><th>Gate Pass</th><th>Vehicle Owner</th><th>Vehicle</th><th>Status</th></tr></thead><tbody>';
                $plateCounts = $records->groupBy('plate_number')->map(fn($g) => $g->count())->sortDesc()->take(50);
                $rank = 1;
                foreach ($plateCounts as $plate => $count) {
                    $vehicle = $registeredVehicles->get($plate);
                    $ownerName = $vehicle?->user?->details?->full_name ?? '<span class="badge badge-unreg">Unregistered</span>';
                    $gatePass = $vehicle?->assigned_gate_pass ?? 'None';
                    $vehicleInfo = $vehicle ? e($vehicle->vehicle_info) : 'N/A';
                    $statusBadge = '';
                    if ($vehicle) {
                        if ($vehicle->isExpired()) {
                            $statusBadge = '<span class="badge badge-expired">Expired</span>';
                        } elseif ($vehicle->isExpiringSoon()) {
                            $statusBadge = '<span class="badge badge-expiring">Expiring Soon</span>';
                        } else {
                            $statusBadge = '<span class="badge badge-active">Active</span>';
                        }
                    } else {
                        $statusBadge = '<span class="badge badge-unreg">N/A</span>';
                    }
                    echo '<tr><td>' . $rank++ . '</td><td><strong>' . e($plate) . '</strong></td><td>' . $count . '</td><td>' . e($gatePass) . '</td><td>' . $ownerName . '</td><td>' . $vehicleInfo . '</td><td>' . $statusBadge . '</td></tr>';
                }
                echo '</tbody></table>';
            } else {
                // Detailed / Flagged records table
                echo '<h2>Detection Records</h2>';
                echo '<table><thead><tr><th>Time</th><th>Plate</th><th>Confidence</th><th>Gate</th><th>Direction</th><th>Status</th><th>Owner</th><th>Vehicle</th><th>Gate Pass</th></tr></thead><tbody>';
                foreach ($records as $record) {
                    $vehicle = $registeredVehicles->get($record->plate_number);
                    $ownerName = $vehicle?->user?->details?->full_name ?? '';
                    $gatePass = $vehicle?->assigned_gate_pass ?? '';
                    $vehicleInfo = $vehicle ? e($vehicle->vehicle_info) : '';
                    $statusBadge = $record->is_flagged
                        ? '<span class="badge badge-flagged">Flagged</span>'
                        : '<span class="badge badge-normal">Normal</span>';

                    echo '<tr>';
                    echo '<td>' . ($record->detected_at?->format('Y-m-d H:i:s') ?? $record->created_at->format('Y-m-d H:i:s')) . '</td>';
                    echo '<td><strong>' . e($record->plate_number) . '</strong></td>';
                    echo '<td>' . round($record->confidence * 100, 1) . '%</td>';
                    echo '<td>' . e($record->gate?->gate_name ?? 'Unknown') . '</td>';
                    echo '<td>' . e($record->gate?->gate_location ?? 'Unknown') . '</td>';
                    echo '<td>' . $statusBadge . '</td>';
                    echo '<td>' . e($ownerName) . '</td>';
                    echo '<td>' . $vehicleInfo . '</td>';
                    echo '<td>' . e($gatePass) . '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            }

            echo '<div class="footer">ANPR System &mdash; Report generated on ' . now()->format('M d, Y H:i:s') . '</div>';
            echo '</body></html>';
        }, $filename, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Get hourly scan data.
     */
    protected function getHourlyData(): array
    {
        $data = [];
        for ($i = 6; $i <= 22; $i++) {
            $hour = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            $data[] = [
                'hour' => $hour,
                'entries' => rand(5, 50),
                'exits' => rand(5, 45),
            ];
        }
        return $data;
    }

    /**
     * Get gate distribution data.
     */
    protected function getGateDistribution(): array
    {
        return [
            ['gate' => 'Main Gate', 'count' => rand(100, 200), 'percentage' => 60],
            ['gate' => 'Back Gate', 'count' => rand(40, 80), 'percentage' => 25],
            ['gate' => 'Service Gate', 'count' => rand(20, 40), 'percentage' => 15],
        ];
    }

    /**
     * Get vehicle type distribution.
     */
    protected function getVehicleTypeDistribution(): array
    {
        return [
            ['type' => 'Car', 'count' => rand(100, 150), 'percentage' => 55],
            ['type' => 'Motorcycle', 'count' => rand(50, 80), 'percentage' => 30],
            ['type' => 'Truck', 'count' => rand(15, 30), 'percentage' => 10],
            ['type' => 'Other', 'count' => rand(5, 15), 'percentage' => 5],
        ];
    }
}
