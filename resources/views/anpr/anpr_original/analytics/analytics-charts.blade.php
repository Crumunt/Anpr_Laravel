@props([
    'vehicle_activity_data' => [156, 189, 203, 178, 195, 98, 65],
    'vehicle_type_data' => [65, 20, 12, 3],
    'alert_trends_data' => [8, 14, 12, 8, 26],
    'entrance_performance_data' => [45, 35, 20]
])

<style>
    /* Minimal styles for layout and modal */
    .analytics-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
      padding: 1rem;
    }
    .analytics-card {
      background: #fff;
      border-radius: 12px;
      padding: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      display: flex;
      flex-direction: column;
      min-height: 240px;
    }
    .chart-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; }
    .chart-title { font-weight:600; display:flex; gap:8px; align-items:center; }
    .chart-container { position:relative; height: 260px; }
    .chart-loading { position:absolute; inset:0; display:flex; flex-direction:column; justify-content:center; align-items:center; background: rgba(255,255,255,0.7); z-index:5; }
    .chart-metrics { display:flex; gap:8px; margin-top:10px; flex-wrap:wrap; }

    /* Modal */
    .chart-modal {
      position:fixed;
      inset:0;
      display:none;
      align-items:center;
      justify-content:center;
      background: rgba(0,0,0,0.5);
      z-index:2000;
      padding: 20px;
    }
    .chart-modal-content {
      width:100%;
      max-width:1100px;
      background:#fff;
      border-radius:10px;
      overflow:hidden;
      box-shadow:0 8px 30px rgba(0,0,0,0.25);
    }
    .chart-modal-header {
      display:flex;
      justify-content:space-between;
      align-items:center;
      padding:12px 16px;
      border-bottom:1px solid #eee;
    }
    .chart-modal-body { padding:16px; }
    .close-chart-modal-btn { background:none; border:0; font-size:18px; cursor:pointer; }

    /* make sure modal's chart container has a good height */
    #chartModalContent .chart-container { height: 500px !important; min-height: 400px; }

    /* Debug button */
    .debug-btn { position: fixed; top: 10px; right: 10px; z-index: 10000; background: blue; color: white; padding: 10px; border: none; border-radius: 5px; cursor:pointer; }
  </style>

<div id="analyticsData"
	data-vehicle-activity='@json($vehicle_activity_data)'
	data-vehicle-type='@json($vehicle_type_data)'
	data-alert-trends='@json($alert_trends_data)'
	data-entrance-performance='@json($entrance_performance_data)'
></div>

<div class="analytics-grid">
    <!-- Vehicle Activity Chart -->
    <div class="analytics-card enhanced-chart-card" data-chart-id="vehicleActivityChart">
        <div class="chart-header">
            <div class="chart-title">
                <i class="fas fa-chart-line"></i>
                Vehicle Activity (Last 7 Days)
            </div>
            <div class="chart-actions">
                <button class="chart-action-btn" title="Export Data" onclick="exportChartData('vehicleActivityChart')">
                    <i class="fas fa-download"></i>
                </button>
                <button class="chart-action-btn fullscreen-btn" title="Full Screen" onclick="openChartModal('vehicleActivityChart')">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
        </div>
        <div class="chart-container enhanced-chart">
            <div class="chart-loading">
                <div class="loading-spinner"></div>
                <p class="loading-text">Loading chart...</p>
            </div>
            <canvas id="vehicleActivityChart"></canvas>
        </div>
        <div class="chart-metrics">
            <div class="metric-item enhanced-metric">
                <div class="metric-icon pulse-animation">
                    <i class="fas fa-clock text-blue-500"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Peak Hours</span>
                    <span class="metric-value">8:00 AM - 10:00 AM</span>
                </div>
            </div>
            <div class="metric-item enhanced-metric">
                <div class="metric-icon pulse-animation">
                    <i class="fas fa-car text-green-500"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Average Daily Traffic</span>
                    <span class="metric-value">184 vehicles</span>
                </div>
            </div>
            <div class="metric-item enhanced-metric">
                <div class="metric-icon pulse-animation">
                    <i class="fas fa-calendar-week text-purple-500"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Weekend Activity</span>
                    <span class="metric-value">-45%</span>
                </div>
                <span class="metric-change negative">↓ 12%</span>
            </div>
        </div>
    </div>

    <!-- Vehicle Type Distribution -->
    <div class="analytics-card enhanced-chart-card" data-chart-id="vehicleTypeChart">
        <div class="chart-header">
            <div class="chart-title">
                <i class="fas fa-chart-pie"></i>
                Vehicle Type Distribution
            </div>
            <div class="chart-actions">
                <button class="chart-action-btn" title="Export Data" onclick="exportChartData('vehicleTypeChart')">
                    <i class="fas fa-download"></i>
                </button>
                <button class="chart-action-btn fullscreen-btn" title="Full Screen" onclick="openChartModal('vehicleTypeChart')">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
        </div>
        <div class="chart-container enhanced-chart">
            <div class="chart-loading">
                <div class="loading-spinner"></div>
                <p class="loading-text">Loading chart...</p>
            </div>
            <canvas id="vehicleTypeChart"></canvas>
        </div>
        <div class="chart-metrics">
            <div class="metric-item enhanced-metric">
                <div class="metric-icon pulse-animation">
                    <i class="fas fa-car text-green-600"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Regular Vehicles</span>
                    <span class="metric-value">65%</span>
                </div>
                <span class="metric-change positive">↑ 8%</span>
            </div>
            <div class="metric-item enhanced-metric">
                <div class="metric-icon pulse-animation">
                    <i class="fas fa-user-clock text-blue-600"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Visitor Vehicles</span>
                    <span class="metric-value">20%</span>
                </div>
                <span class="metric-change positive">↑ 3%</span>
            </div>
            <div class="metric-item enhanced-metric">
                <div class="metric-icon pulse-animation">
                    <i class="fas fa-user-tie text-purple-600"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Staff Vehicles</span>
                    <span class="metric-value">12%</span>
                </div>
                <span class="metric-change neutral">→ 0%</span>
            </div>
            <div class="metric-item enhanced-metric">
                <div class="metric-icon pulse-animation">
                    <i class="fas fa-question-circle text-red-600"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Unknown Vehicles</span>
                    <span class="metric-value">3%</span>
                </div>
                <span class="metric-change negative">↓ 2%</span>
            </div>
        </div>
    </div>

    <!-- Alert Trends -->
    <div class="analytics-card enhanced-chart-card" data-chart-id="alertTrendsChart">
        <div class="chart-header">
            <div class="chart-title">
                <i class="fas fa-exclamation-triangle"></i>
                Alert Trends
            </div>
            <div class="chart-actions">
                <button class="chart-action-btn" title="Export Data" onclick="exportChartData('alertTrendsChart')">
                    <i class="fas fa-download"></i>
                </button>
                <button class="chart-action-btn fullscreen-btn" title="Full Screen" onclick="openChartModal('alertTrendsChart')">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
        </div>
        <div class="chart-container enhanced-chart">
            <div class="chart-loading">
                <div class="loading-spinner"></div>
                <p class="loading-text">Loading chart...</p>
            </div>
            <canvas id="alertTrendsChart"></canvas>
        </div>
        <div class="chart-metrics">
            <div class="metric-item enhanced-metric">
                <div class="metric-icon pulse-animation">
                    <i class="fas fa-radiation text-red-600"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Critical Alerts</span>
                    <span class="metric-value">8</span>
                </div>
                <span class="metric-change negative">↑ 33%</span>
            </div>
            <div class="metric-item enhanced-metric">
                <div class="metric-icon pulse-animation">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Resolved Alerts</span>
                    <span class="metric-value">26</span>
                </div>
                <span class="metric-change positive">↑ 18%</span>
            </div>
            <div class="metric-item enhanced-metric">
                <div class="metric-icon pulse-animation">
                    <i class="fas fa-stopwatch text-blue-600"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Response Time</span>
                    <span class="metric-value">2.3 min</span>
                </div>
                <span class="metric-change positive">↓ 15%</span>
            </div>
        </div>
    </div>

    <!-- Entrance Performance -->
    <div class="analytics-card enhanced-chart-card" data-chart-id="entrancePerformanceChart">
        <div class="chart-header">
            <div class="chart-title">
                <i class="fas fa-door-open"></i>
                Entrance Performance
            </div>
            <div class="chart-actions">
                <button class="chart-action-btn" title="Export Data" onclick="exportChartData('entrancePerformanceChart')">
                    <i class="fas fa-download"></i>
                </button>
                <button class="chart-action-btn fullscreen-btn" title="Full Screen" onclick="openChartModal('entrancePerformanceChart')">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
        </div>
        <div class="chart-container enhanced-chart">
            <div class="chart-loading">
                <div class="loading-spinner"></div>
                <p class="loading-text">Loading chart...</p>
            </div>
            <canvas id="entrancePerformanceChart"></canvas>
        </div>
        <div class="chart-metrics">
            <div class="metric-item enhanced-metric">
                <div class="metric-icon pulse-animation">
                    <i class="fas fa-door-open text-green-600"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Main Entrance</span>
                    <span class="metric-value">45%</span>
                </div>
                <span class="metric-change positive">↑ 5%</span>
            </div>
            <div class="metric-item enhanced-metric">
                <div class="metric-icon pulse-animation">
                    <i class="fas fa-sign-out-alt text-blue-600"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Exit Gate</span>
                    <span class="metric-value">35%</span>
                </div>
                <span class="metric-change positive">↑ 2%</span>
            </div>
            <div class="metric-item enhanced-metric">
                <div class="metric-icon pulse-animation">
                    <i class="fas fa-route text-purple-600"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Back Gate</span>
                    <span class="metric-value">20%</span>
                </div>
                <span class="metric-change negative">↓ 3%</span>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Chart Modal -->
<div id="chartModal" class="chart-modal" aria-hidden="true">
    <div class="chart-modal-content" role="dialog" aria-modal="true" aria-labelledby="chartModalTitle">
        <div class="chart-modal-header">
            <h3 id="chartModalTitle">Chart Details</h3>
            <button class="close-chart-modal-btn" aria-label="Close" onclick="closeChartModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="chart-modal-body">
            <div id="chartModalContent">
                <!-- Cloned card + new canvas will be inserted here by JS -->
            </div>
        </div>
    </div>
</div>

<!-- Debug/Test Button -->

<!-- Inline script removed; logic moved to public/js/anpr/analytics.js -->