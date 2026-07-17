@extends('layouts.app')

@section('title', 'Tambah Barang Baru')
@section('page_title', 'Tambah Barang')
@section('page_subtitle', 'Masukkan informasi barang baru untuk ditambahkan ke data inventaris.')

@section('content')
    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header">
            <h2>Formulir Barang Baru</h2>
            <a href="{{ route('items.index') }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">Kembali</a>
        </div>

        <form action="{{ route('items.store') }}" method="POST">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="sku">SKU / Kode Barang (Opsional)</label>
                    <input 
                        type="text" 
                        id="sku" 
                        name="sku" 
                        class="form-control" 
                        placeholder="Contoh: BRG-001 (Biarkan kosong untuk otomatis)"
                        value="{{ old('sku') }}"
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
                        placeholder="Masukkan nama lengkap barang"
                        required
                        value="{{ old('name') }}"
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
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
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
                        placeholder="Contoh: Pcs, Box, Kg, Liter, Unit"
                        required
                        value="{{ old('unit', 'Pcs') }}"
                    >
                    @error('unit')
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
                        placeholder="Contoh: 15000"
                        required
                        min="0"
                        value="{{ old('price', 0) }}"
                    >
                    @error('price')
                        <span style="color: var(--danger); font-size: 12px; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="qty">Stok Awal <span style="color: var(--danger)">*</span></label>
                    <input 
                        type="number" 
                        id="qty" 
                        name="qty" 
                        class="form-control" 
                        placeholder="Contoh: 10"
                        required
                        min="0"
                        value="{{ old('qty', 0) }}"
                    >
                    @error('qty')
                        <span style="color: var(--danger); font-size: 12px; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi Barang</label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-control" 
                    placeholder="Deskripsi spesifikasi barang atau lokasi penyimpanan..."
                >{{ old('description') }}</textarea>
                @error('description')
                    <span style="color: var(--danger); font-size: 12px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 20px;">
                <a href="{{ route('items.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Tambah Barang</button>
            </div>
        </form>
    </div>
@endsection
