<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Locations;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'street' => 'required',
            'building' => 'required',
            'area' => 'required'
        ]);

        try {
            $location = Locations::create([
                'street' => $request->street,
                'building' => $request->building,
                'area' => $request->area,
                'user_id' => Auth::id()
            ]);
            return response()->json('location added', 201);
        } catch(Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function updateLocation($id, Request $request) {
        $validated = $request->validate([
            'street' => 'required',
            'building' => 'required',
            'area' => 'required'
        ]);

        try {
            $location = Locations::findOrFail($id);
            $location->street = $request->street;
            $location->building = $request->building;
            $location->area = $request->area;
            $location->save();
            return response()->json('location updated', 200);
        } catch(Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function destroy($id) {
        $location = Locations::find($id);
        if($location) {
            $location->delete();
            return response()->json('location deleted', 200);
        } else {
            return response()->json('location not found', 500);
        }
    }
}
