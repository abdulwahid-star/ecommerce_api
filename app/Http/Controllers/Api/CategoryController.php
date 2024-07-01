<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class CategoryController extends Controller
{
    public function index() {
        $category = Categories::paginate(10);
        return response()->json($category, 200);
    }

    public function show($id) {
        $category = Categories::find($id);
        if($category) {
            return response()->json($category, 200);
        } else {
            return response()->json('category not found');
        }
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|unique:categories,name',
            'image' => 'required'
        ]);

        try {
            $category = new Categories;
            $path = 'assets/uploads/category' . $category->image;
            if(File::exists($path)) {
                File::delete($path);
            };
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $ext;
            try {
                $file->move('assets/uploads/category', $fileName);
            } catch (FileException $e) {
                dd($e);
            }
            $category->image = $fileName;
            $category->name = $request->name;
            $category->save();
            return response()->json('category added', 201);
        } catch(Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function updateCategory($id, Request $request) {
        $validated = $request->validate([
            'name' => 'required|unique:categories,name',
            'image' => 'required'
        ]);

        try {
            $category = Categories::findOrFail($id);
            if($request->hasFile('image')) {
                $path = 'assets/uploads/category' . $category->image;
                if(File::exists($path)) {
                    File::delete($path);
                };
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $ext;
                try {
                    $file->move('assets/uploads/category', $fileName);
                } catch (FileException $e) {
                    dd($e);
                }
                $category->image = $fileName;
            }
            $category->name = $request->name;
            $category->update();
            return response()->json('brand updated', 200);
        } catch(Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function destroy($id) {
        $category = Categories::find($id);
        if($category) {
            $category->delete();
            return response()->json('brand deleted', 200);
        } else {
            return response()->json('brand not found', 500);
        }
    }
}
