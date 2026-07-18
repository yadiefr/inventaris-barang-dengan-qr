@extends('layouts.app')

@section('title', 'Manajemen Kategori')
@section('page_title', 'Kategori Barang')
@section('page_subtitle', 'Kelola kategori barang untuk memudahkan pengelompokan inventaris.')

@section('content')
    <div class="layout-grid">
        <!-- Categories List -->
        <div class="card">
            <div class="card-header">
                <h2>Daftar Kategori</h2>
            </div>
            
            @if($categories->isEmpty())
                <div style="padding: 30px; text-align: center; color: var(--text-muted);">
                    Belum ada kategori. Silakan tambahkan kategori baru di sebelah kanan.
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th style="width: 120px; text-align: center;">Jumlah Barang</th>
                                <th style="width: 150px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td data-label="ID">#{{ $category->id }}</td>
                                    <td data-label="Nama Kategori"><strong>{{ $category->name }}</strong></td>
                                    <td data-label="Deskripsi">{{ $category->description ?? '-' }}</td>
                                    <td data-label="Jumlah Barang" style="text-align: center;">
                                        <span class="badge badge-info">{{ $category->items_count }}</span>
                                    </td>
                                    <td data-label="Aksi" style="text-align: center;">
                                        <div style="display: flex; gap: 8px; justify-content: center;">
                                            <button 
                                                onclick="editCategory({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description) }}')" 
                                                class="btn btn-secondary" 
                                                style="padding: 6px 12px; font-size: 12px;"
                                            >
                                                Edit
                                            </button>
                                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
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
            @endif
        </div>

        <!-- Form Card (Dynamic for Create / Edit) -->
        <div class="card" id="form-card">
            <div class="card-header">
                <h2 id="form-title">Tambah Kategori</h2>
            </div>
            
            <form id="category-form" action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div id="method-field"></div>
                
                <div class="form-group">
                    <label for="name">Nama Kategori <span style="color: var(--danger)">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Contoh: Elektronik, ATK, Makanan" required value="{{ old('name') }}">
                    @error('name')
                        <span style="color: var(--danger); font-size: 12px; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" class="form-control" placeholder="Deskripsi singkat kategori">{{ old('description') }}</textarea>
                    @error('description')
                        <span style="color: var(--danger); font-size: 12px; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" id="submit-btn" class="btn btn-primary" style="flex-grow: 1; justify-content: center;">Simpan</button>
                    <button type="button" id="cancel-btn" onclick="resetForm()" class="btn btn-secondary" style="display: none;">Batal</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const formCard = document.getElementById('form-card');
        const formTitle = document.getElementById('form-title');
        const categoryForm = document.getElementById('category-form');
        const methodField = document.getElementById('method-field');
        const nameInput = document.getElementById('name');
        const descInput = document.getElementById('description');
        const submitBtn = document.getElementById('submit-btn');
        const cancelBtn = document.getElementById('cancel-btn');

        // Original store route
        const storeRoute = "{{ route('categories.store') }}";

        function editCategory(id, name, description) {
            // Change title & form action
            formTitle.innerText = "Edit Kategori";
            categoryForm.action = `/categories/${id}`;
            
            // Add PUT method
            methodField.innerHTML = `@method('PUT')`;
            
            // Populate fields
            nameInput.value = name;
            descInput.value = description;
            
            // Show cancel button
            submitBtn.innerText = "Perbarui";
            cancelBtn.style.display = "inline-flex";

            // Scroll form card into view for mobile devices
            formCard.scrollIntoView({ behavior: 'smooth' });
            nameInput.focus();
        }

        function resetForm() {
            formTitle.innerText = "Tambah Kategori";
            categoryForm.action = storeRoute;
            methodField.innerHTML = '';
            
            nameInput.value = '';
            descInput.value = '';
            
            submitBtn.innerText = "Simpan";
            cancelBtn.style.display = "none";
        }
    </script>
@endsection
