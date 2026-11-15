<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table            = 'produk';
    protected $primaryKey       = 'kode_produk';
    protected $allowedFields    = ['kode_produk', 'nama_produk', 'harga_jual', 'stok'];
    protected $useAutoIncrement = false;

    // Fungsi pencarian produk berdasarkan nama
    public function cariProduk($keyword = null)
    {
        if ($keyword) {
            return $this->like('nama_produk', $keyword);
        }
        return $this;
    }
}