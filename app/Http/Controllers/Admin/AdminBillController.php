<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBillController extends Controller
{
    public function index()
    {
        $bills = Bill::latest()->get();
        return view('admin.bills.index', compact('bills'));
    }

    public function create()
    {
        return view('admin.bills.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255', // Dari input name="name" di view
            'category' => 'required|string',
        ]);

        Bill::create([
            'bill_code' => 'BILL-' . strtoupper(Str::random(6)),
            'bill_name' => $request->name,
            'category'  => strtolower($request->category),
            'icon'      => 'fas fa-file-invoice', // Default icon sesuai view Anda
            'is_active' => true,
        ]);

        return redirect()->route('admin.bills.index')->with('success', 'Layanan tagihan berhasil dibuat.');
    }

    public function edit(Bill $bill)
    {
        return view('admin.bills.edit', compact('bill'));
    }

    public function update(Request $request, Bill $bill)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'required|string',
        ]);

        $bill->update([
            'bill_name' => $request->name,
            'category'  => strtolower($request->category),
        ]);

        return redirect()->route('admin.bills.index')->with('success', 'Layanan tagihan diperbarui.');
    }

    public function destroy(Bill $bill)
    {
        $bill->delete();
        return back()->with('success', 'Layanan tagihan berhasil dihapus.');
    }
}