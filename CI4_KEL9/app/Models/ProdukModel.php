<?php

namespace App\Models;

use CodeIgniter\Model;

// Model tabel produk: otomatis punya find/findAll/insert/update/delete.
class ProdukModel extends Model
{
    protected $table            = 'produk';
    protected $primaryKey       = 'kode_produk';
    // Kode produk diisi manual (bukan auto increment).
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    // Kolom yang boleh diisi lewat insert/update.
    protected $allowedFields    = [
        'kode_produk', 'nama_produk', 'satuan',
        'harga_jual', 'harga_beli', 'stok', 'deskripsi',
    ];

    // Pencarian nama produk. Parameter terakhir (true) membuat pencarian
    // tidak case-sensitive, sama seperti ILIKE di PHP Native.
    public function cariProduk(?string $keyword = null)
    {
        if ($keyword) {
            return $this->like('nama_produk', $keyword, 'both', null, true);
        }
        // Return $this agar Controller bisa lanjut ->orderBy()->findAll().
        return $this;
    }
}