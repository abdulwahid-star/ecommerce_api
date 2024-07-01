<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProductController extends Controller
{
    public function index() {
        $product = Products::paginate(10);
        return response()->json($product, 200);
    }

    public function show($id) {
        $product = Products::find($id);
        if($product) {
            return response()->json($product, 200);
        } else {
            return response()->json('category not found');
        }
    }

    public function store(Request $request) {
        Validator::make($request->all(), [
            'category_id' => 'required|numeric',
            'brand_id' => 'required|numeric',
            'name' => 'required',
            'image' => 'required',
            'price' => 'required|numeric',
            'amount' => 'required|numeric',
            'discount' => 'required|numeric'
        ]);

        try {
            $product = new Products;
            $path = 'assets/uploads/product' . $product->image;
            if(File::exists($path)) {
                File::delete($path);
            };
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $ext;
            try {
                $file->move('assets/uploads/product', $fileName);
            } catch (FileException $e) {
                dd($e);
            }
            $product->image = $fileName;
            $product->name = $request->name;
            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            $product->price = $request->price;
            $product->amount = $request->amount;
            $product->discount = $request->discount;
            $product->save();
            return response()->json('product added', 201);
        } catch(Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function updateProduct($id, Request $request) {
        Validator::make($request->all(), [
            'category_id' => 'required|numeric',
            'brand_id' => 'required|numeric',
            'name' => 'required',
            'image' => 'required',
            'price' => 'required|numeric',
            'amount' => 'required|numeric',
            'discount' => 'required|numeric'
        ]);

        try {
            $product = Products::findOrFail($id);
            if($request->hasFile('image')) {
                $path = 'assets/uploads/product' . $product->image;
                if(File::exists($path)) {
                    File::delete($path);
                };
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $ext;
                try {
                    $file->move('assets/uploads/product', $fileName);
                } catch (FileException $e) {
                    dd($e);
                }
                $product->image = $fileName;
            }
            $product->name = $request->name;
            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            $product->price = $request->price;
            $product->amount = $request->amount;
            $product->discount = $request->discount;
            $product->update();
            return response()->json('product updated', 200);
        } catch(Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function destroy($id) {
        $product = Products::find($id);
        if($product) {
            $product->delete();
            return response()->json('product deleted', 200);
        } else {
            return response()->json('product not found', 500);
        }
    }
}
