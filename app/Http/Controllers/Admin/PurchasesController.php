<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchasesController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['supplier', 'items'])->latest()->get();
        return view('admin.purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('admin.purchases.create-edit-view', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:purchase_date',
            'tax_percentage' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $subtotal = array_reduce($request->items, function ($carry, $item) {
                return $carry + ($item['quantity'] * $item['price']);
            }, 0);

            $tax_amount = ($subtotal * $request->tax_percentage) / 100;
            $total = $subtotal + $tax_amount - ($request->discount ?? 0);

            // Generate unique purchase number (you might want to create a helper for this)
            $lastPurchase = Purchase::latest()->first();
            $purchaseNumber = $lastPurchase 
                ? 'PUR' . str_pad((intval(substr($lastPurchase->purchase_number, 3)) + 1), 6, '0', STR_PAD_LEFT)
                : 'PUR000001';

            $purchase = Purchase::create([
                'purchase_number' => $purchaseNumber,
                'supplier_id' => $request->supplier_id,
                'purchase_date' => $request->purchase_date,
                'due_date' => $request->due_date,
                'subtotal' => $subtotal,
                'tax_percentage' => $request->tax_percentage,
                'tax_amount' => $tax_amount,
                'discount' => $request->discount,
                'total' => $total,
                'notes' => $request->notes,
                'status' => 'pending'
            ]);

            foreach ($request->items as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price'],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.purchases.index')->with('success', 'Purchase created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Purchase creation failed: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $purchase = Purchase::with('items.product')->findOrFail($id);
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('admin.purchases.create-edit-view', compact('purchase', 'suppliers', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:purchase_date',
            'tax_percentage' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $purchase = Purchase::findOrFail($id);

            $subtotal = array_reduce($request->items, function ($carry, $item) {
                return $carry + ($item['quantity'] * $item['price']);
            }, 0);

            $tax_amount = ($subtotal * $request->tax_percentage) / 100;
            $total = $subtotal + $tax_amount - ($request->discount ?? 0);

            $purchase->update([
                'supplier_id' => $request->supplier_id,
                'purchase_date' => $request->purchase_date,
                'due_date' => $request->due_date,
                'subtotal' => $subtotal,
                'tax_percentage' => $request->tax_percentage,
                'tax_amount' => $tax_amount,
                'discount' => $request->discount,
                'total' => $total,
                'notes' => $request->notes,
            ]);

            // Delete existing items not in the request
            $existingItemIds = array_column($request->items, 'id');
            $purchase->items()->whereNotIn('id', array_filter($existingItemIds))->delete();

            foreach ($request->items as $item) {
                if (isset($item['id'])) {
                    $existingItem = PurchaseItem::find($item['id']);
                    $existingItem->update([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total' => $item['quantity'] * $item['price'],
                    ]);
                } else {
                    PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total' => $item['quantity'] * $item['price'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.purchases.index')->with('success', 'Purchase updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Purchase update failed: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $purchase = Purchase::findOrFail($id);
            $purchase->items()->delete();
            $purchase->delete();

            DB::commit();
            return redirect()->route('admin.purchases.index')->with('success', 'Purchase deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Purchase deletion failed: ' . $e->getMessage()]);
        }
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:purchases,id',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        try {
            $purchase = Purchase::findOrFail($request->id);
            $purchase->update(['status' => $request->status]);
            
            return response()->json(['message' => 'Purchase status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Status update failed: ' . $e->getMessage()], 500);
        }
    }
}