<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    // RETRIEVE ALL ITEMS AND DISPLAY THEM IN A VIEW
    public function index()
    {
        $items = Item::all();
        return view('admin.items.index', compact('items'));
    }

    // VALIDATE AND STORE A NEW ITEM
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'unit' => 'required|string|max:50',
        ]);

        $item = new Item();
        $item->name = $request->name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->unit = $request->unit;

        $item->save();

        return redirect()->route('admin.items.index')->with('success', 'Item created successfully!');
    }

    // SHOW A FORM FOR EDITING THE SPECIFIED ITEM
    public function edit($id)
    {
        $item = Item::findOrFail($id);
        return view('admin.items.edit', compact('item'));
    }

    // VALIDATE AND UPDATE THE SPECIFIED ITEM
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'unit' => 'required|string|max:50',
        ]);

        $item = Item::findOrFail($id);
        $item->name = $request->name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->unit = $request->unit;

        $item->save();

        return redirect()->route('admin.items.index')->with('success', 'Item updated successfully!');
    }

    // DELETE THE SPECIFIED ITEM
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.items.index')->with('success', 'Item deleted successfully!');
    }
}
