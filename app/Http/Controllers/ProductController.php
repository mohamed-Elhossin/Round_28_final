<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::all();

        if ($product->isEmpty()) {
            $response  = [
                "status" => 200,
                'message' => "No Products Found"
            ];
        } else {
            $response  = [
                "status" => 200,
                "data" => $product,
                'message' => "Get All Products Successfully"
            ];
        }

        return response($response, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::find($id);

        if ($product == null) {
            $response  = [
                "status" => 200,
                'message' => "No Products Found By $id"
            ];
        } else {
            $response  = [
                "status" => 200,
                "data" => $product,
                'message' => "Get  Products Successfully"
            ];
        }

        return response($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => "required|string",
            'description' => 'required|string',
            'image' => "required|file"
        ]);
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;

        $image_data = $request->file('image');
        $image_name = time() . $image_data->getClientOriginalName();
        $location = public_path('upload');
        $image_data->move($location, $image_name);
        $product->image = $image_name;
        $product->save();
        $response  = [
            "status" => 200,
            "data" => $product,
            'message' => "Create Products Successfully"
        ];
        return response($response, 200);
    }



    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => "required|string",
            'description' => 'required|string',

        ]);
        $product = Product::find($id);

        $product->name = $request->name;
        $product->description = $request->description;
        $image_data = $request->file('image');
        if ($image_data == null) {

            $image_name =      $product->image;
        } else {
            $OLD =      $product->image;
            $fullPath = public_path('upload/') . $OLD;
            unlink($fullPath);
            $image_name = time() . $image_data->getClientOriginalName();
            $location = public_path('upload');
            $image_data->move($location, $image_name);
        }
        $product->image = $image_name;
        $product->save();

        $response  = [
            "status" => 200,
            "data" => $product,
            'message' => "Update Products Successfully"
        ];
        return response($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        if ($product == null) {
            $response  = [
                "status" => 200,
                'message' => "No Products Found By $id"
            ];
        } else {
            $response  = [
                "status" => 200,
                "data" => $product,
                'message' => "Delete  Products Successfully"
            ];
        }
        return response($response, 200);
    }
}
