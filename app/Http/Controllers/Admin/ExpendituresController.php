<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expenditure;
use Illuminate\Http\Request;

class ExpendituresController extends Controller
{
    // RETRIEVE ALL EXPENDITURES AND DISPLAY THEM IN A VIEW
    public function index()
    {
        if (auth()->user()->user_role == 1) {
            $expenditures = Expenditure::latest()->get();
        } else {
            $expenditures = Expenditure::where('user_id', auth()->id())->latest()->get();
        }

        return view('admin.expenditures.index', compact('expenditures'));
    }

    public function create()
    {
        return view('admin.expenditures.create-edit-view');
    }

    // VALIDATE AND STORE A NEW EXPENDITURE
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'category' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            'invoice_number' => 'required|string|unique:expenditures,invoice_number|max:255',
        ]);

        $expenditure = new Expenditure();
        $expenditure->description = $request->description;
        $expenditure->amount = $request->amount;
        $expenditure->date = $request->date;
        $expenditure->category = $request->category;
        $expenditure->payment_method = $request->payment_method;
        $expenditure->user_id = auth()->id();
        $expenditure->invoice_number = $request->invoice_number;

        $expenditure->save();

        return redirect()->route('admin.expenditures.index')->with('success', 'Expenditure added successfully!');
    }

    // SHOW A FORM FOR EDITING THE SPECIFIED EXPENDITURE
    public function edit($id)
    {
        $isEdit = true;
        $expenditure = Expenditure::findOrFail($id);
        return view('admin.expenditures.create-edit-view', compact('expenditure', 'isEdit'));
    }

    // VIEW A SPECIFIC EXPENDITURE
    public function view($id)
    {
        $isEdit = false;
        $expenditure = Expenditure::findOrFail($id);
        return view('admin.expenditures.create-edit-view', compact('expenditure', 'isEdit'));
    }

    // VALIDATE AND UPDATE THE SPECIFIED EXPENDITURE
    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'category' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            'invoice_number' => 'required|string|unique:expenditures,invoice_number,' . $id . '|max:255',
        ]);

        $expenditure = Expenditure::findOrFail($id);
        $expenditure->description = $request->description;
        $expenditure->amount = $request->amount;
        $expenditure->date = $request->date;
        $expenditure->category = $request->category;
        $expenditure->payment_method = $request->payment_method;
        $expenditure->invoice_number = $request->invoice_number;

        $expenditure->save();

        return redirect()->route('admin.expenditures.index')->with('success', 'Expenditure updated successfully!');
    }

    // DELETE THE SPECIFIED EXPENDITURE
    public function destroy($id)
    {
        $expenditure = Expenditure::findOrFail($id);
        $expenditure->delete();

        return redirect()->route('admin.expenditures.index')->with('success', 'Expenditure deleted successfully!');
    }
}
