<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    // RETRIEVE ALL PRODUCTS AND DISPLAY THEM IN A VIEW
    public function index()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    // VALIDATE AND STORE A NEW PRODUCT
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'unit' => 'required|string|max:50',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->unit = $request->unit;

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    // SHOW A FORM FOR EDITING THE SPECIFIED PRODUCT
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    // VALIDATE AND UPDATE THE SPECIFIED PRODUCT
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'unit' => 'required|string|max:50',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->unit = $request->unit;

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    // DELETE THE SPECIFIED PRODUCT
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }
}