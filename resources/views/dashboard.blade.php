@extends('layouts.app')

@section('title', 'Dashboard Ringkasan')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Tinjauan statistik inventaris barang dan aktivitas terbaru.')

@section('content')
    <!-- Stat Grid -->
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-info">
                <p>Total Jenis Barang</p>
                <h3>{{ $totalItems }}</h3>
            </div>
            <div class="stat-icon icon-blue">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <p>Total Kategori</p>
                <h3>{{ $totalCategories }}</h3>
            </div>
            <div class="stat-icon icon-purple">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <p>Total Unit Stok</p>
                <h3>{{ $totalStock }}</h3>
            </div>
            <div class="stat-icon icon-green">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <p>Stok Menipis (Low Stock)</p>
                <h3 style="color: {{ $lowStockItems->count() > 0 ? 'var(--danger)' : 'var(--text-main)' }}">
                    {{ $lowStockItems->count() }}
                </h3>
            </div>
            <div class="stat-icon icon-danger">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
        </div>
    </div>

    <!-- Layout Grid -->
    <div class="layout-grid">
        <!-- Recent Activities -->
        <div class="card">
            <div class="card-header">
                <h2>Aktivitas Stok Terbaru</h2>
                <a href="{{ route('items.index') }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">Lihat Barang</a>
            </div>
            
            @if($recentHistories->isEmpty())
                <div style="padding: 20px; text-align: center; color: var(--text-muted);">
                    Belum ada riwayat aktivitas mutasi barang.
                </div>
            @else
                <ul class="history-timeline">
                    @foreach($recentHistories as $history)
                        <li class="history-item {{ $history->type }}">
                            <div class="history-time">{{ $history->created_at->diffForHumans() }} ({{ $history->created_at->format('d M Y, H:i') }})</div>
                            <div class="history-desc">
                                <strong>{{ $history->item->name }}</strong> 
                                <span class="badge {{ $history->type === 'in' ? 'badge-success' : 'badge-danger' }}" style="padding: 2px 8px; font-size: 11px;">
                                    {{ $history->type === 'in' ? '+' : '-' }}{{ $history->qty }} {{ $history->item->unit }}
                                </span>
                            </div>
                            <div style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">
                                Catatan: {{ $history->notes }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Low Stock Alert Panel -->
        <div class="card">
            <div class="card-header">
                <h2>Peringatan Stok Menipis</h2>
            </div>
            
            @if($lowStockItems->isEmpty())
                <div style="padding: 20px; text-align: center; color: var(--success); font-weight: 500; font-size: 14px;">
                    ✔ Semua stok dalam kondisi aman!
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach($lowStockItems as $item)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background-color: var(--bg-card-hover); border-radius: 8px; border-left: 4px solid var(--danger);">
                            <div>
                                <h4 style="font-size: 14px; font-weight: 600;">
                                    <a href="{{ route('items.show', $item->id) }}" style="color: inherit; text-decoration: none;">
                                        {{ $item->name }}
                                    </a>
                                </h4>
                                <span style="font-size: 11px; color: var(--text-muted);">SKU: {{ $item->sku }}</span>
                            </div>
                            <div style="text-align: right;">
                                <span class="badge badge-danger">{{ $item->qty }} {{ $item->unit }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
