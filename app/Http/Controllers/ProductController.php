<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = auth()->user()->products;
        return response()->json([
            "status" => true,
            "data" => $products
        ]);
    }

    public function show($id)
    {
        $product = auth()->user()->products()->find($id);
        if (!isset($product)) {
            return response()->json([
                "status" => false,
                "data" => "No Product"
            ], 400);
        }
        return response()->json([
            "status" => true,
            "data" => $product->toArray()
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|integer',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;

        if (auth()->user()->products()->save($product)) {
            return response()->json([
                "success" => true,
                "data" => $product->toArray(),
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "data" => "Products could not found or stored",
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $product = auth()->user()->products()->find($id);

        if (!isset($product)) {
            return response()->json([
                "status" => false,
                "data" => "No Product"
            ], 400);
        }

        $updated = $product->fill($request->all())->save();

        if ($updated){
            return response()->json([
                "success" => true,
                "data" => "Updated successfully",
            ], 200);
        }else{
            return response()->json([
                "success" => false,
                "data" => "Could not update the product",
            ], 500);
        }
    }

    public function destroy($id){
        $product = auth()->user()->products()->find($id);
        if (!isset($product)) {
            return response()->json([
                "status" => false,
                "data" => "No Product"
            ], 400);
        }
        if ($product->delete()){
            return response()->json([
                "success" => true,
                "data" => "Deleted successfully",
            ], 200);
        }else{
            return response()->json([
                "success" => false,
                "data" => "Could not delete the product",
            ], 500);
        }
    }
}
