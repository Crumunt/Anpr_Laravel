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
			->where(function ($qb) use ($q) {
				$qb->where('first_name', 'like', "{$q}%")
					->orWhere('last_name', 'like', "{$q}%")
					->orWhere('email', 'like', "%{$q}%")
					->orWhereHas('details', fn($d) => $d->where('clsu_id', 'like', "{$q}%"));
			})
			->limit(5)
			->get()
			->map(function (User $u) {
				return [
					'type' => 'Applicant',
					'id' => $u->id,
					'label' => $u->full_name,
					'sublabel' => $u->email,
					'url' => route('admin.applicant.show', $u->id),
				];
			});

		$vehicles = Vehicle::query()
			->with('user')
			->where(function ($qb) use ($q) {
				$qb->where('license_plate', 'like', "%{$q}%")
					->orWhere('vehicle_make', 'like', "%{$q}%")
					->orWhere('vehicle_model', 'like', "%{$q}%");
			})
			->limit(5)
			->get()
			->map(function (Vehicle $v) {
				return [
					'type' => 'Vehicle',
					'id' => $v->id,
					'label' => trim(($v->vehicle_make ?? '') . ' ' . ($v->vehicle_model ?? '')),
					'sublabel' => $v->license_plate,
					'url' => route('admin.vehicles.show', $v->id),
				];
			});

		$results = $users->merge($vehicles)->take(5)->values();

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
				->where(function ($qb) use ($q) {
					$qb->where('first_name', 'like', "{$q}%")
						->orWhere('last_name', 'like', "{$q}%")
						->orWhere('email', 'like', "%{$q}%")
						->orWhereHas('details', fn($d) => $d->where('clsu_id', 'like', "{$q}%"));
				})
				->limit(50)
				->get();

			$vehicleResults = Vehicle::query()
				->with('user')
				->where(function ($qb) use ($q) {
					$qb->where('license_plate', 'like', "%{$q}%")
						->orWhere('vehicle_make', 'like', "%{$q}%")
						->orWhere('vehicle_model', 'like', "%{$q}%");
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


