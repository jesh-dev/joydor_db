<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class productController extends Controller
{
    // Create Product method

    public function create(Request $request) {

        //return "Hello Labs"
        $validator = Validator::make($request->all(),[
            'product_name' => 'required|string',
            'product_category' => 'required|string',
            'product_desc' => 'nullable|string',
            'initial_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'product_image' => 'required|image|mimes:jpeg,jpg,png|
            max:5120',
            'vendor_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors
            ()], 400);
        }

        try {
            $product = new product;
            $product->product_name = $request->input('product_name');
            $product->product_category = $request->input
            ('product_category');
            $product->product_desc = $request->input('product_desc');
            $product->initial_price = $request->input
            ('initial_price');
            $product->selling_price = $request->input
            ('selling_price');
            $product->quantity = $request->input('quantity');
            $product->product_image = $request->file('product_image')
            ->store('products_images');
            $product->vendor_id = $request->input('vendor_id');
            $product->save();

            return response()->json([
                'product' => $product,
                'message' => "Product Added Successfully"

            ], 201);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()],
            500);
        }

    }
   
}
