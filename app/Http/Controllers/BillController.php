<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BillController extends Controller
{
    public function __construct()
    {
        // CRUD tagihan hanya untuk admin, kecuali index (user bisa lihat)
        $this->middleware(function ($request, $next) {
            if (!in_array($request->route()->getActionMethod(), ['index']) && !auth()->user()->isAdmin()) {
                abort(403, 'Hanya admin yang dapat mengelola tagihan.');
            }
            return $next($request);
        });
    }

    private const CATEGORIES = ['listrik', 'air', 'telepon', 'internet', 'bpjs', 'pajak', 'pendidikan', 'lainnya'];

    /**
     * Daftar tagihan aktif (bisa diakses user untuk memilih tagihan).
     */
    public function index(Request $request)
    {
        $bills = Bill::query()
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->when($request->search, fn($q) => $q->where('bill_name', 'like', '%' . $request->search . '%')
                ->orWhere('bill_code', 'like', '%' . $request->search . '%'))
            ->when(!auth()->user()->isAdmin(), fn($q) => $q->active())
            ->orderBy('category')
            ->orderBy('bill_name')
            ->get()
            ->groupBy('category');

        $categories = collect(self::CATEGORIES);

        return view('bills.index', compact('bills', 'categories'));
    }

    /**
     * Form tambah tagihan (Admin).
     */
    public function create()
    {
        $categories = self::CATEGORIES;

        return view('bills.create', compact('categories'));
    }

    /**
     * Simpan tagihan baru (Admin).
     */
    public function store(Request $request)
    {
        $request->validate([
            'bill_code' => ['required', 'string', 'max:20', 'unique:bills,bill_code'],
            'bill_name' => ['required', 'string', 'max:255'],
            'category'  => ['required', Rule::in(self::CATEGORIES)],
            'icon'      => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Bill::create([
            'bill_code' => strtoupper($request->bill_code),
            'bill_name' => $request->bill_name,
            'category'  => $request->category,
            'icon'      => $request->icon,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.bills.index')
            ->with('success', 'Tagihan ' . $request->bill_name . ' berhasil ditambahkan.');
    }

    /**
     * Form edit tagihan (Admin).
     */
    public function edit(Bill $bill)
    {
        $categories = self::CATEGORIES;

        return view('bills.edit', compact('bill', 'categories'));
    }

    /**
     * Update tagihan (Admin).
     */
    public function update(Request $request, Bill $bill)
    {
        $request->validate([
            'bill_code' => ['required', 'string', 'max:20', Rule::unique('bills', 'bill_code')->ignore($bill->id)],
            'bill_name' => ['required', 'string', 'max:255'],
            'category'  => ['required', Rule::in(self::CATEGORIES)],
            'icon'      => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $bill->update([
            'bill_code' => strtoupper($request->bill_code),
            'bill_name' => $request->bill_name,
            'category'  => $request->category,
            'icon'      => $request->icon,
            'is_active' => $request->boolean('is_active', $bill->is_active),
        ]);

        return redirect()->route('admin.bills.index')
            ->with('success', 'Tagihan berhasil diperbarui.');
    }

    /**
     * Nonaktifkan tagihan (soft delete — Admin).
     */
    public function destroy(Bill $bill)
    {
        if ($bill->payments()->exists()) {
            // Ada riwayat pembayaran — nonaktifkan saja, jangan hapus
            $bill->update(['is_active' => false]);
            return back()->with('success', 'Tagihan dinonaktifkan karena memiliki riwayat pembayaran.');
        }

        $bill->delete();

        return back()->with('success', 'Tagihan berhasil dihapus.');
    }
}
