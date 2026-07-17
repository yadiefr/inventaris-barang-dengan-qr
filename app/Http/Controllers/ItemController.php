<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\StockHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ItemController extends Controller
{
    /**
     * Display a listing of the items.
     */
    public function index(Request $request): View
    {
        $query = Item::with('category');

        // Search logic
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Stock status filter
        if ($request->has('stock_status') && $request->stock_status != '') {
            if ($request->stock_status == 'low') {
                $query->where('qty', '<=', 5);
            } elseif ($request->stock_status == 'empty') {
                $query->where('qty', '=', 0);
            }
        }

        $items = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();
        $categories = Category::orderBy('name', 'asc')->get();

        return view('items.index', compact('items', 'categories'));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('items.create', compact('categories'));
    }

    /**
     * Store a newly created item in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sku' => 'nullable|string|max:255|unique:items,sku',
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
        ]);

        $item = Item::create($validated);

        // If initial quantity is greater than 0, create an initial stock history
        if ($item->qty > 0) {
            StockHistory::create([
                'item_id' => $item->id,
                'type' => 'in',
                'qty' => $item->qty,
                'notes' => 'Stok awal barang ditambahkan'
            ]);
        }

        return redirect()->route('items.show', $item->id)->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified item.
     */
    public function show(Item $item): View
    {
        $item->load(['category', 'stockHistories']);
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(Item $item): View
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified item in storage.
     */
    public function update(Request $request, Item $item): RedirectResponse
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:255|unique:items,sku,' . $item->id,
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
        ]);

        $oldQty = $item->qty;
        $newQty = $validated['qty'];

        $item->update($validated);

        // Record stock adjustment if quantity changed
        if ($newQty != $oldQty) {
            $diff = $newQty - $oldQty;
            StockHistory::create([
                'item_id' => $item->id,
                'type' => $diff > 0 ? 'in' : 'out',
                'qty' => abs($diff),
                'notes' => 'Penyesuaian manual jumlah stok'
            ]);
        }

        return redirect()->route('items.show', $item->id)->with('success', 'Barang berhasil diperbarui!');
    }

    /**
     * Remove the specified item from storage.
     */
    public function destroy(Item $item): RedirectResponse
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus!');
    }

    /**
     * Show the QR Scanner view.
     */
    public function scan(): View
    {
        return view('items.scan');
    }

    /**
     * Find item by SKU (for QR scanner redirection)
     */
    public function findBySku(string $sku)
    {
        $item = Item::where('sku', $sku)->first();

        if ($item) {
            return response()->json([
                'success' => true,
                'redirect_url' => route('items.show', $item->id)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Barang dengan SKU ' . $sku . ' tidak ditemukan.'
        ], 404);
    }

    /**
     * Download the QR Code image.
     */
    public function downloadQr(Item $item): Response
    {
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->margin(1)
            ->generate($item->sku);

        return response($qrCode)
            ->header('Content-type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="qr-' . $item->sku . '.svg"');
    }
}
