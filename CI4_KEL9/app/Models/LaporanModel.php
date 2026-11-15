<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    protected $table            = 'penjualan';
    protected $primaryKey       = 'no_penjualan';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';

    // 1. JOIN: transaksi penjualan beserta nama pelanggan
    public function daftarPenjualan(): array
    {
        return $this->db->table('penjualan p')
            ->select('p.no_penjualan, p.tgl_penjualan, c.nama_pelanggan, p.total')
            ->join('pelanggan c', 'c.kode_pelanggan = p.kode_pelanggan')
            ->orderBy('p.tgl_penjualan', 'ASC')
            ->orderBy('p.no_penjualan', 'ASC')
            ->get()->getResultArray();
    }

    // 2. Agregasi: jumlah transaksi dan total penjualan per pelanggan
    public function rekapPelanggan(): array
    {
        return $this->db->table('pelanggan c')
            ->select('c.kode_pelanggan, c.nama_pelanggan, 
                      COUNT(p.no_penjualan) AS jumlah_transaksi, 
                      COALESCE(SUM(p.total), 0) AS total_penjualan', false)
            ->join('penjualan p', 'p.kode_pelanggan = c.kode_pelanggan', 'left')
            ->groupBy('c.kode_pelanggan, c.nama_pelanggan')
            ->orderBy('total_penjualan', 'DESC')
            ->orderBy('c.nama_pelanggan', 'ASC')
            ->get()->getResultArray();
    }

    // 3. JOIN + agregasi: total kuantitas dan nilai penjualan per produk
    public function rekapProduk(): array
    {
        return $this->db->table('produk pr')
            ->select('pr.kode_produk, pr.nama_produk, 
                      COALESCE(SUM(d.jumlah), 0) AS total_unit_terjual, 
                      COALESCE(SUM(d.subtotal), 0) AS total_nilai', false)
            ->join('detil_penjualan d', 'd.kode_produk = pr.kode_produk', 'left')
            ->groupBy('pr.kode_produk, pr.nama_produk')
            ->orderBy('total_unit_terjual', 'DESC')
            ->orderBy('pr.nama_produk', 'ASC')
            ->get()->getResultArray();
    }
}