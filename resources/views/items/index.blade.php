@extends('layouts.app')

@section('title', 'Daftar Barang')
@section('page_title', 'Inventaris Barang')
@section('page_subtitle', 'Kelola semua barang inventaris Anda, unduh QR Code, dan filter pencarian.')

@section('content')
    <!-- Filter and Search Bar -->
    <form action="{{ route('items.index') }}" method="GET" class="filter-bar">
        <div class="filter-search">
            <input 
                type="text" 
                name="search" 
                class="form-control" 
                placeholder="Cari berdasarkan nama, SKU, lokasi, atau deskripsi..." 
                value="{{ request('search') }}"
            >
        </div>
        
        <div class="filter-select">
            <select name="category_id" class="form-control" onchange="this.form.submit()">
                <option value="">-- Semua Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-select">
            <select name="stock_status" class="form-control" onchange="this.form.submit()">
                <option value="">-- Status Stok --</option>
                <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Menipis (<= 5)</option>
                <option value="empty" {{ request('stock_status') == 'empty' ? 'selected' : '' }}>Stok Habis (= 0)</option>
            </select>
        </div>

        <div>
            <button type="submit" class="btn btn-secondary">Filter</button>
            @if(request('search') || request('category_id') || request('stock_status'))
                <a href="{{ route('items.index') }}" class="btn btn-secondary" style="border-color: var(--danger); color: #fca5a5;">Reset</a>
            @endif
        </div>
    </form>

    <!-- Items Card -->
    <div class="card full-width">
        <div class="card-header">
            <h2>Semua Data Barang</h2>
            <a href="{{ route('items.create') }}" class="btn btn-primary">
                <svg class="btn-svg" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Tambah Barang</span>
            </a>
        </div>

        @if($items->isEmpty())
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                Tidak ada data barang yang ditemukan.
            </div>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 140px;">SKU / Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Lokasi / Tempat</th>
                            <th style="text-align: right;">Harga Satuan</th>
                            <th style="text-align: center; width: 120px;">Stok</th>
                            <th style="text-align: center; width: 120px;">QR Code</th>
                            <th style="width: 220px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td data-label="SKU / Kode">
                                    <code style="font-family: monospace; font-size: 14px; font-weight: bold; color: var(--primary); letter-spacing: 0.5px;">
                                        {{ $item->sku }}
                                    </code>
                                </td>
                                <td data-label="Nama Barang">
                                    <div class="item-name-cell">
                                        <div class="item-name">{{ $item->name }}</div>
                                        <div class="item-desc">
                                            {{ $item->description ?? '' }}
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Kategori">
                                    @if($item->category)
                                        <span class="badge badge-info" style="background-color: rgba(103, 232, 249, 0.08); border: 1px solid rgba(103, 232, 249, 0.3);">
                                            {{ $item->category->name }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary" style="background-color: var(--border-color); color: var(--text-muted);">
                                            Umum
                                        </span>
                                    @endif
                                </td>
                                <td data-label="Lokasi / Tempat">
                                    @if($item->location)
                                        <span style="font-weight: 500; color: var(--text-main); font-size: 13px;">
                                            📍 {{ $item->location }}
                                        </span>
                                    @else
                                        <span style="color: var(--text-muted); font-size: 13px;">—</span>
                                    @endif
                                </td>
                                <td data-label="Harga Satuan" style="text-align: right; font-weight: 500;">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td data-label="Stok" style="text-align: center;">
                                    @if($item->qty == 0)
                                        <span class="badge badge-danger">Habis (0 {{ $item->unit }})</span>
                                    @elseif($item->qty <= 5)
                                        <span class="badge badge-warning">Menipis ({{ $item->qty }} {{ $item->unit }})</span>
                                    @else
                                        <span class="badge badge-success">{{ $item->qty }} {{ $item->unit }}</span>
                                    @endif
                                </td>
                                <td data-label="QR Code" style="text-align: center;">
                                    <div style="background-color: white; padding: 4px; border-radius: 4px; display: inline-flex; justify-content: center; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                        {!! QrCode::size(40)->generate($item->sku) !!}
                                    </div>
                                </td>
                                <td data-label="Aksi" style="text-align: center;">
                                    <div class="action-buttons">
                                        <a href="{{ route('items.show', $item->id) }}" class="btn btn-secondary btn-detail" style="padding: 6px 12px; font-size: 12px;">
                                            Detail & QR
                                        </a>
                                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                                            Edit
                                        </a>
                                        <form action="{{ route('items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Menampilkan {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} dari {{ $items->total() }} barang
                </div>
                <div class="pagination-links">
                    {{-- Simple pagination layout --}}
                    @if ($items->onFirstPage())
                        <span class="disabled">Sebelumnya</span>
                    @else
                        <a href="{{ $items->previousPageUrl() }}">Sebelumnya</a>
                    @endif

                    @if ($items->hasMorePages())
                        <a href="{{ $items->nextPageUrl() }}">Berikutnya</a>
                    @else
                        <span class="disabled">Berikutnya</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
