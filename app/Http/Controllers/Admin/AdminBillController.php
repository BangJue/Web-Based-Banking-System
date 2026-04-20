<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Http\Request;

class AdminBillController extends Controller
{
    /**
     * Menampilkan daftar semua layanan tagihan.
     */
    public function index()
    {
        $bills = Bill::latest()->get();
        return view('admin.bills.index', compact('bills'));
    }

    /**
     * Menampilkan form tambah layanan.
     */
    public function create()
    {
        return view('admin.bills.create');
    }

    /**
     * Menyimpan layanan baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:Listrik,Air,Internet,Pajak',
            'admin_fee' => 'required|numeric|min:0',
        ]);

        Bill::create($validated);

        return redirect()->route('admin.bills.index')
            ->with('success', 'Layanan tagihan baru berhasil ditambahkan.');
    }

    /**
     * Menghapus layanan tagihan.
     * Alur ini lebih aman: Jika salah, hapus lalu buat baru.
     */
    public function destroy(Bill $bill)
    {
        $bill->delete();

        return redirect()->route('admin.bills.index')
            ->with('success', 'Layanan tagihan berhasil dihapus.');
    }
}