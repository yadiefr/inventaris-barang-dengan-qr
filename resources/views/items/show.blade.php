@extends('layouts.app')

@section('title', 'Detail Barang: ' . $item->name)
@section('page_title', 'Detail Barang')
@section('page_subtitle', 'Melihat detail spesifikasi barang, QR Code, dan riwayat pergerakan stok.')

@section('content')
    <div class="detail-grid">
        <!-- Left Column: QR Code & Stock Adjustments -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- QR Card -->
            <div class="qr-panel">
                <div class="qr-code-box">
                    {!! QrCode::size(200)->generate($item->sku) !!}
                </div>
                <div class="sku-text">{{ $item->sku }}</div>
                <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 20px;">
                    Scan QR Code ini untuk melihat halaman detail barang secara instan.
                </p>
                <div style="display: flex; width: 100%;">
                    <a href="{{ route('items.download-qr', $item->id) }}" class="btn btn-primary" style="width: 100%; justify-content: center;">
                        <svg class="btn-svg" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                        <span>Unduh JPG</span>
                    </a>
                </div>
            </div>

            <!-- Quick Stock Adjustments Form -->
            <div class="card">
                <div class="card-header">
                    <h2>Transaksi / Mutasi Stok</h2>
                </div>
                
                <form action="{{ route('items.stock.store', $item->id) }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="type">Jenis Mutasi <span style="color: var(--danger)">*</span></label>
                        <select id="type" name="type" class="form-control" required>
                            <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>Stok Masuk (+)</option>
                            <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Stok Keluar (-)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="qty">Jumlah ({{ $item->unit }}) <span style="color: var(--danger)">*</span></label>
                        <input 
                            type="number" 
                            id="qty" 
                            name="qty" 
                            class="form-control" 
                            placeholder="Masukkan jumlah barang" 
                            required 
                            min="1" 
                            value="{{ old('qty') }}"
                        >
                    </div>

                    <div class="form-group">
                        <label for="notes">Keterangan / Alasan</label>
                        <input 
                            type="text" 
                            id="notes" 
                            name="notes" 
                            class="form-control" 
                            placeholder="Contoh: Pembelian baru, Retur, Barang rusak"
                            value="{{ old('notes') }}"
                        >
                    </div>

                    <button type="submit" class="btn btn-success" style="width: 100%; justify-content: center; font-weight: bold;">
                        Simpan Mutasi
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: Item Information & Timeline -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Specification Card -->
            <div class="card">
                <div class="card-header">
                    <h2>Spesifikasi Barang</h2>
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">Edit Barang</a>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table style="border-collapse: separate; border-spacing: 0 10px;">
                        <tbody>
                            <tr>
                                <td style="width: 180px; color: var(--text-muted); font-weight: 500; border: none; padding: 6px 0;">Nama Barang:</td>
                                <td style="border: none; padding: 6px 0;"><strong>{{ $item->name }}</strong></td>
                            </tr>
                            <tr>
                                <td style="color: var(--text-muted); font-weight: 500; border: none; padding: 6px 0;">SKU / Kode Barang:</td>
                                <td style="border: none; padding: 6px 0;"><code style="color: var(--primary); font-weight: bold; font-size: 15px;">{{ $item->sku }}</code></td>
                            </tr>
                            <tr>
                                <td style="color: var(--text-muted); font-weight: 500; border: none; padding: 6px 0;">Kategori:</td>
                                <td style="border: none; padding: 6px 0;">
                                    @if($item->category)
                                        <span class="badge badge-info">{{ $item->category->name }}</span>
                                    @else
                                        <span class="badge badge-secondary">Umum</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="color: var(--text-muted); font-weight: 500; border: none; padding: 6px 0;">Lokasi / Tempat:</td>
                                <td style="border: none; padding: 6px 0;">
                                    @if($item->location)
                                        <span class="badge badge-secondary" style="background-color: var(--bg-card-hover); border: 1px solid var(--border-color); color: var(--text-main); font-weight: 600;">
                                            📍 {{ $item->location }}
                                        </span>
                                    @else
                                        <span style="color: var(--text-muted); font-style: italic;">Belum ditentukan</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="color: var(--text-muted); font-weight: 500; border: none; padding: 6px 0;">Harga Satuan:</td>
                                <td style="border: none; padding: 6px 0; font-weight: 600;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td style="color: var(--text-muted); font-weight: 500; border: none; padding: 6px 0;">Stok Saat Ini:</td>
                                <td style="border: none; padding: 6px 0;">
                                    @if($item->qty == 0)
                                        <span class="badge badge-danger">Habis (0 {{ $item->unit }})</span>
                                    @elseif($item->qty <= 5)
                                        <span class="badge badge-warning">Menipis ({{ $item->qty }} {{ $item->unit }})</span>
                                    @else
                                        <span class="badge badge-success">{{ $item->qty }} {{ $item->unit }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="color: var(--text-muted); font-weight: 500; border: none; padding: 6px 0; vertical-align: top;">Deskripsi:</td>
                                <td style="border: none; padding: 6px 0; color: var(--text-muted); font-size: 14px; line-height: 1.5;">
                                    {{ $item->description ?? 'Tidak ada deskripsi.' }}
                                </td>
                            </tr>
                            <tr>
                                <td style="color: var(--text-muted); font-weight: 500; border: none; padding: 6px 0;">Ditambahkan Pada:</td>
                                <td style="border: none; padding: 6px 0; font-size: 13px; color: var(--text-muted);">{{ $item->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mutasi Stock History Timeline -->
            <div class="card history-card">
                <div class="card-header">
                    <h2>Riwayat Mutasi Barang</h2>
                </div>
                
                @if($item->stockHistories->isEmpty())
                    <div style="padding: 20px; text-align: center; color: var(--text-muted);">
                        Belum ada riwayat transaksi stok barang ini.
                    </div>
                @else
                    <ul class="history-timeline">
                        @foreach($item->stockHistories as $history)
                            <li class="history-item {{ $history->type }}">
                                <div class="history-time">{{ $history->created_at->format('d M Y, H:i') }} ({{ $history->created_at->diffForHumans() }})</div>
                                <div class="history-desc">
                                    Stok 
                                    <strong>{{ $history->type === 'in' ? 'Masuk' : 'Keluar' }}</strong>
                                    <span class="badge {{ $history->type === 'in' ? 'badge-success' : 'badge-danger' }}" style="padding: 2px 6px; font-size: 11px;">
                                        {{ $history->type === 'in' ? '+' : '-' }}{{ $history->qty }} {{ $item->unit }}
                                    </span>
                                </div>
                                <div style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">
                                    Keterangan: {{ $history->notes }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection
