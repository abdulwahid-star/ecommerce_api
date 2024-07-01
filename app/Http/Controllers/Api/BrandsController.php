<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brands;

class BrandsController extends Controller
{
    public function index() {
        $brands = Brands::paginate(10);
        return response()->json($brands, 200);
    }

    public function show($id) {
        $brands = Brands::find($id);
        if($brands) {
            return response()->json($brands, 200);
        } else {
            return response()->json('brand not found');
        }
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|unique:brands,name'
        ]);

        try {
            $brands = new Brands;
            $brands->name = $request->name;
            $brands->save();
            return response()->json('brand added', 201);
        } catch(Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function updateBrand($id, Request $request) {
        $validated = $request->validate([
            'name' => 'required|unique:brands,name'
        ]);

        try {
            $brands = Brands::findOrFail($id);
            $brands->name = $request->name;
            $brands->save();
            return response()->json('brand updated', 200);
        } catch(Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function destroy($id) {
        $brands = Brands::find($id);
        if($brands) {
            $brands->delete();
            return response()->json('brand deleted', 200);
        } else {
            return response()->json('brand not found', 500);
        }
    }
}
