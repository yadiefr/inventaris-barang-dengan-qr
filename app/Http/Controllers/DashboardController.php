<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard view with statistics.
     */
    public function index(): View
    {
        $totalItems = Item::count();
        $totalCategories = Category::count();
        $totalStock = Item::sum('qty');
        
        // Define low stock warning limit (e.g. 5 units)
        $lowStockItems = Item::with('category')
            ->where('qty', '<=', 5)
            ->orderBy('qty', 'asc')
            ->limit(5)
            ->get();
            
        // Get recent stock updates
        $recentHistories = StockHistory::with('item')
            ->latest()
            ->limit(8)
            ->get();

        return view('dashboard', compact(
            'totalItems',
            'totalCategories',
            'totalStock',
            'lowStockItems',
            'recentHistories'
        ));
    }
}
