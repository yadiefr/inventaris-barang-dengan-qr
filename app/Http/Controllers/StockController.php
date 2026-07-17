<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Store a stock transaction (In or Out).
     */
    public function store(Request $request, Item $item): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:in,out',
            'qty' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        $qty = $validated['qty'];
        $type = $validated['type'];

        if ($type === 'out') {
            if ($item->qty < $qty) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Stok tidak mencukupi untuk melakukan pengeluaran barang. Sisa stok saat ini: ' . $item->qty . ' ' . $item->unit);
            }
            $item->qty -= $qty;
            $actionWord = 'dikurangi';
        } else {
            $item->qty += $qty;
            $actionWord = 'ditambah';
        }

        // Save item new qty
        $item->save();

        // Log transaction history
        StockHistory::create([
            'item_id' => $item->id,
            'type' => $type,
            'qty' => $qty,
            'notes' => $validated['notes'] ?? ($type === 'in' ? 'Stok masuk' : 'Stok keluar'),
        ]);

        return redirect()->route('items.show', $item->id)
            ->with('success', "Stok barang berhasil {$actionWord} sebanyak {$qty} {$item->unit}!");
    }
}
