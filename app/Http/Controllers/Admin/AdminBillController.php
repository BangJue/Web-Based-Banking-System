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
            'name'      => 'required|string|max:255',
            'category'  => 'required|string',
            'admin_fee' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        Bill::create([
            'bill_code' => 'BILL-' . strtoupper(Str::random(6)),
            'bill_name' => $request->name,
            'category'  => strtolower($request->category),
            'icon'      => $this->iconFromCategory(strtolower($request->category)),
            'admin_fee' => (int) $request->admin_fee,
            'is_active' => $request->boolean('is_active', true),
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
            'name'      => 'required|string|max:255',
            'category'  => 'required|string',
            'admin_fee' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $bill->update([
            'bill_name' => $request->name,
            'category'  => strtolower($request->category),
            'icon'      => $this->iconFromCategory(strtolower($request->category)),
            'admin_fee' => (int) $request->admin_fee,
            'is_active' => $request->boolean('is_active', $bill->is_active),
        ]);

        return redirect()->route('admin.bills.index')->with('success', 'Layanan tagihan diperbarui.');
    }

    public function destroy(Bill $bill)
    {
        $bill->delete();
        return back()->with('success', 'Layanan tagihan berhasil dihapus.');
    }

    // ── Helper: icon FA sesuai kategori ──────────────────────────────────────

    private function iconFromCategory(string $category): string
    {
        return match ($category) {
            'listrik'    => 'fas fa-bolt',
            'air'        => 'fas fa-tint',
            'internet'   => 'fas fa-wifi',
            'telepon'    => 'fas fa-phone',
            'bpjs'       => 'fas fa-heartbeat',
            'pajak'      => 'fas fa-landmark',
            'pendidikan' => 'fas fa-graduation-cap',
            default      => 'fas fa-file-invoice',
        };
    }
}