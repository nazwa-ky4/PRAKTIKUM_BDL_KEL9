<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table            = 'produk';
    protected $primaryKey       = 'kode_produk';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
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
        return $this;
    }
}