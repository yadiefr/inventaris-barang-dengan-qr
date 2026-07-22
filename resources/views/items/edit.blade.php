@extends('layouts.app')

@section('title', 'Edit Barang: ' . $item->name)
@section('page_title', 'Edit Barang')
@section('page_subtitle', 'Ubah informasi barang atau sesuaikan stok secara manual.')

@section('content')
    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header">
            <h2>Edit Data: {{ $item->name }}</h2>
            <a href="{{ route('items.show', $item->id) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">Batal</a>
        </div>

        <form action="{{ route('items.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label for="sku">SKU / Kode Barang <span style="color: var(--danger)">*</span></label>
                    <input 
                        type="text" 
                        id="sku" 
                        name="sku" 
                        class="form-control" 
                        required
                        value="{{ old('sku', $item->sku) }}"
                    >
                    @error('sku')
                        <span style="color: var(--danger); font-size: 12px; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">Nama Barang <span style="color: var(--danger)">*</span></label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-control" 
                        required
                        value="{{ old('name', $item->name) }}"
                    >
                    @error('name')
                        <span style="color: var(--danger); font-size: 12px; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="category_id">Kategori Barang</label>
                    <select id="category_id" name="category_id" class="form-control">
                        <option value="">-- Tanpa Kategori (Umum) --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span style="color: var(--danger); font-size: 12px; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="location">Tempat / Lokasi Barang</label>
                    <input 
                        type="text" 
                        id="location" 
                        name="location" 
                        class="form-control" 
                        placeholder="Contoh: Rak A-02, Gudang 1, Lemari B"
                        value="{{ old('location', $item->location) }}"
                    >
                    @error('location')
                        <span style="color: var(--danger); font-size: 12px; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="price">Harga Satuan (Rp) <span style="color: var(--danger)">*</span></label>
                    <input 
                        type="number" 
                        id="price" 
                        name="price" 
                        class="form-control" 
                        required
                        min="0"
                        value="{{ old('price', $item->price) }}"
                    >
                    @error('price')
                        <span style="color: var(--danger); font-size: 12px; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="unit">Satuan Barang <span style="color: var(--danger)">*</span></label>
                    <input 
                        type="text" 
                        id="unit" 
                        name="unit" 
                        class="form-control" 
                        required
                        value="{{ old('unit', $item->unit) }}"
                    >
                    @error('unit')
                        <span style="color: var(--danger); font-size: 12px; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="qty">Stok saat ini <span style="color: var(--danger)">*</span></label>
                <input 
                    type="number" 
                    id="qty" 
                    name="qty" 
                    class="form-control" 
                    required
                    min="0"
                    value="{{ old('qty', $item->qty) }}"
                >
                <small style="color: var(--text-muted); font-size: 11px; display: block; margin-top: 4px;">
                    Catatan: Mengubah jumlah stok di sini akan otomatis mencatat mutasi penyesuaian stok.
                </small>
                @error('qty')
                    <span style="color: var(--danger); font-size: 12px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Deskripsi Barang</label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-control"
                >{{ old('description', $item->description) }}</textarea>
                @error('description')
                    <span style="color: var(--danger); font-size: 12px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 20px;">
                <a href="{{ route('items.show', $item->id) }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
