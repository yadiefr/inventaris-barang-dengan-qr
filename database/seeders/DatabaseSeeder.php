<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\StockHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. Create Default Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('AdminInventory2026!'),
        ]);

        // 1. Create Categories
        $categories = [
            Category::create(['name' => 'Elektronik & Gadget', 'description' => 'Peralatan elektronik, komputer, hp, dan aksesorisnya.']),
            Category::create(['name' => 'Alat Tulis Kantor', 'description' => 'Peralatan penunjang administrasi kantor.']),
            Category::create(['name' => 'Perlengkapan Medis', 'description' => 'Masker, hand sanitizer, p3k, obat-obatan ringan.']),
            Category::create(['name' => 'Suku Cadang (Sparepart)', 'description' => 'Komponen mesin dan suku cadang cadangan.']),
            Category::create(['name' => 'Konsumsi & Logistik', 'description' => 'Makanan ringan, minuman, kopi, gula untuk pantry.']),
        ];

        // 2. Create Items & Initial Stock History
        $itemsData = [
            [
                'name' => 'Laptop ASUS Vivobook 14',
                'category_id' => $categories[0]->id,
                'location' => 'Rak A-02',
                'description' => 'Laptop kerja Core i5, RAM 8GB, SSD 512GB.',
                'price' => 8750000,
                'qty' => 4, // Low stock alert trigger
                'unit' => 'Unit'
            ],
            [
                'name' => 'Logitech Wireless Mouse M185',
                'category_id' => $categories[0]->id,
                'location' => 'Rak A-03',
                'description' => 'Mouse nirkabel warna hitam-abu.',
                'price' => 145000,
                'qty' => 12,
                'unit' => 'Pcs'
            ],
            [
                'name' => 'Kertas HVS Sinar Dunia A4 80gr',
                'category_id' => $categories[1]->id,
                'location' => 'Rak B-01',
                'description' => 'Kertas cetak warna putih bersih.',
                'price' => 52000,
                'qty' => 25,
                'unit' => 'Rim'
            ],
            [
                'name' => 'Buku Catatan Spiral Kiky',
                'category_id' => $categories[1]->id,
                'location' => 'Rak B-02',
                'description' => 'Buku tulis spiral bergaris isi 100 lembar.',
                'price' => 18000,
                'qty' => 40,
                'unit' => 'Pcs'
            ],
            [
                'name' => 'Masker Sensi 3-Ply Earloop',
                'category_id' => $categories[2]->id,
                'location' => 'Lemari C-01',
                'description' => 'Masker medis isi 50 pcs per box.',
                'price' => 35000,
                'qty' => 15,
                'unit' => 'Box'
            ],
            [
                'name' => 'Hand Sanitizer Dettol 500ml',
                'category_id' => $categories[2]->id,
                'location' => 'Lemari C-02',
                'description' => 'Cairan pembersih tangan antiseptik pump.',
                'price' => 65000,
                'qty' => 0, // Out of stock trigger
                'unit' => 'Botol'
            ],
            [
                'name' => 'Oli Mesin Shell Helix HX7 1L',
                'category_id' => $categories[3]->id,
                'location' => 'Gudang D-01',
                'description' => 'Pelumas mesin sintetis untuk mobil dinas kantor.',
                'price' => 95000,
                'qty' => 8,
                'unit' => 'Botol'
            ],
            [
                'name' => 'Kopi Kapal Api Spesial Mix 25gr',
                'category_id' => $categories[4]->id,
                'location' => 'Pantry Lt. 2',
                'description' => 'Kopi instan saset rasa manis.',
                'price' => 2000,
                'qty' => 120,
                'unit' => 'Sachet'
            ],
        ];

        foreach ($itemsData as $data) {
            $item = Item::create($data);

            // Record initial stock creation history
            if ($item->qty > 0) {
                StockHistory::create([
                    'item_id' => $item->id,
                    'type' => 'in',
                    'qty' => $item->qty,
                    'notes' => 'Stok awal barang berhasil didata'
                ]);

                // Create a few random histories to look realistic
                if ($item->qty > 5) {
                    $outQty = rand(1, 3);
                    StockHistory::create([
                        'item_id' => $item->id,
                        'type' => 'out',
                        'qty' => $outQty,
                        'notes' => 'Pengambilan barang untuk kebutuhan divisi'
                    ]);
                    $item->qty -= $outQty;
                    $item->save();
                }
            } else {
                StockHistory::create([
                    'item_id' => $item->id,
                    'type' => 'in',
                    'qty' => 5,
                    'notes' => 'Stok awal masuk'
                ]);
                StockHistory::create([
                    'item_id' => $item->id,
                    'type' => 'out',
                    'qty' => 5,
                    'notes' => 'Stok habis didistribusikan'
                ]);
            }
        }
    }
}
