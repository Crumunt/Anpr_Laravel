<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LocationController extends Controller
{
    //

    protected function loadJson()
    {
        $raw = file_get_contents(storage_path('app/json/cluster.json'));
        $data = json_decode($raw, true);
        return $data;
    }

    public function regions()
    {
        $data = $this->loadJson();
        return response()->json($data);
    }

    public function provinces(Request $request)
    {
        $request->validate(['region_name' => 'required|string']);
        $data = $this->loadJson();
        $region = collect($data)->first(fn($r) => $r['code'] === $request->region_name);
        if (!$region) {
            return response()->json([], 404);
        }

        return response()->json($region['province_list'] ?? []);
    }

    public function cityMunicipalities(Request $request)
    {

        $request->validate(['province_name' => 'required|string']);
        $data = $this->loadJson();
        $province = collect($data)
            ->flatMap(fn($r) => $r['province_list'])
            ->get($request->province_name);

        if(!$province) {
            return response()->json([], 404);
        }

        return response()->json($province['municipality_list'] ?? []);

    }

    public function barangays(Request $request)
    {
        $request->validate(['citymun_name' => 'required|string']);
        $data = $this->loadJson();
        $cityMun = collect($data)
            // removes one layer from json collection
            ->flatMap(fn($c) => $c['province_list'])
            // removes another layer from json collection
            ->flatMap(fn($b) => $b['municipality_list'])
            // get the city with matching name from request
            ->get($request->citymun_name);

        if(!$cityMun) {
            return response()->json([], 404);
        }

        return response()->json($cityMun['barangay_list']);
    }
}
