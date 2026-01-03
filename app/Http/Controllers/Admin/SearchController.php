<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle\Vehicle;
use Illuminate\Http\Request;

class SearchController extends Controller
{
	// AJAX quick search
	public function search(Request $request)
	{
		$q = trim((string) $request->get('q', ''));
		if ($q === '') {
			return response()->json(['results' => []]);
		}

		$users = User::query()
			->with('details')
			->whereHas('details', function ($qb) use ($q) {
				$qb->where('first_name', 'like', "{$q}%")
					->orWhere('last_name', 'like', "{$q}%")
					->orWhere('middle_name', 'like', "{$q}%")
					->orWhere('clsu_id', 'like', "{$q}%");
			})
			->orWhere('email', 'like', "%{$q}%")
			->limit(5)
			->get()
			->map(function (User $u) {
				$details = $u->details;
				$fullName = $details ? $details->full_name : $u->email;
				$clsuId = $details?->clsu_id;

				return [
					'type' => 'Applicant',
					'id' => $u->id,
					'label' => $fullName,
					'sublabel' => $clsuId ? "ID: {$clsuId} • {$u->email}" : $u->email,
					'url' => route('admin.applicant.show', $u->id),
				];
			});

		$vehicles = Vehicle::query()
			->with(['user.details'])
			->where(function ($qb) use ($q) {
				$qb->where('plate_number', 'like', "%{$q}%")
					->orWhere('make', 'like', "%{$q}%")
					->orWhere('model', 'like', "%{$q}%")
					->orWhere('assigned_gate_pass', 'like', "%{$q}%");
			})
			->limit(5)
			->get()
			->map(function (Vehicle $v) {
				$vehicleInfo = $v->vehicle_info ?: trim(($v->make ?? '') . ' ' . ($v->model ?? ''));
				$ownerName = $v->user?->details?->full_name ?? 'Unknown Owner';
				$ownerId = $v->user?->id;
				$gatePass = $v->assigned_gate_pass;

				$sublabel = $v->plate_number ?? 'No plate';
				if ($gatePass) {
					$sublabel .= " • Gate Pass: {$gatePass}";
				}
				$sublabel .= " • {$ownerName}";

				// Redirect to the vehicle owner's applicant page
				$url = $ownerId
					? route('admin.applicant.show', $ownerId)
					: route('admin.applicant');

				return [
					'type' => 'Vehicle',
					'id' => $v->id,
					'label' => $vehicleInfo ?: 'Unknown Vehicle',
					'sublabel' => $sublabel,
					'gatePass' => $gatePass,
					'url' => $url,
				];
			});

		$results = $users->concat($vehicles)->take(5)->values();

		return response()->json(['results' => $results]);
	}

	// Full search results page (optional)
	public function results(Request $request)
	{
		$q = trim((string) $request->get('q', ''));

		$userResults = collect();
		$vehicleResults = collect();
		if ($q !== '') {
			$userResults = User::query()
				->with('details')
				->whereHas('details', function ($qb) use ($q) {
					$qb->where('first_name', 'like', "{$q}%")
						->orWhere('last_name', 'like', "{$q}%")
						->orWhere('middle_name', 'like', "{$q}%")
						->orWhere('clsu_id', 'like', "{$q}%");
				})
				->orWhere('email', 'like', "%{$q}%")
				->limit(50)
				->get();

			$vehicleResults = Vehicle::query()
				->with(['user.details'])
				->where(function ($qb) use ($q) {
					$qb->where('plate_number', 'like', "%{$q}%")
						->orWhere('make', 'like', "%{$q}%")
						->orWhere('model', 'like', "%{$q}%")
						->orWhere('assigned_gate_pass', 'like', "%{$q}%");
				})
				->limit(50)
				->get();
		}

		return view('admin.search.results', [
			'query' => $q,
			'userResults' => $userResults,
			'vehicleResults' => $vehicleResults,
		]);
	}
}


