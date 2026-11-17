<?php

namespace App\Models;

use CodeIgniter\Model;

// Model laporan: JOIN & agregasi dengan Query Builder (hasil akhirnya tetap SQL).
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
            // INNER JOIN: nama pelanggan diambil dari tabel pelanggan.
            ->join('pelanggan c', 'c.kode_pelanggan = p.kode_pelanggan')
            ->orderBy('p.tgl_penjualan', 'ASC')
            ->orderBy('p.no_penjualan', 'ASC')
            ->get()->getResultArray();
    }

    // 2. Agregasi: jumlah transaksi dan total penjualan per pelanggan
    public function rekapPelanggan(): array
    {
        return $this->db->table('pelanggan c')
            // select(..., false): tidak di-quote agar COUNT/SUM/COALESCE valid. COALESCE: NULL -> 0.
            ->select('c.kode_pelanggan, c.nama_pelanggan, 
                      COUNT(p.no_penjualan) AS jumlah_transaksi, 
                      COALESCE(SUM(p.total), 0) AS total_penjualan', false)
            // LEFT JOIN: pelanggan tanpa transaksi tetap tampil (COUNT = 0).
            ->join('penjualan p', 'p.kode_pelanggan = c.kode_pelanggan', 'left')
            // GROUP BY: COUNT/SUM dihitung per pelanggan.
            ->groupBy('c.kode_pelanggan, c.nama_pelanggan')
            ->orderBy('total_penjualan', 'DESC')
            ->orderBy('c.nama_pelanggan', 'ASC')
            ->get()->getResultArray();
    }

    // 3. JOIN + agregasi: total kuantitas dan nilai penjualan per produk
    public function rekapProduk(): array
    {
        return $this->db->table('produk pr')
            // SUM(jumlah) = total qty, SUM(subtotal) = total nilai; COALESCE: belum terjual = 0.
            ->select('pr.kode_produk, pr.nama_produk, 
                      COALESCE(SUM(d.jumlah), 0) AS total_unit_terjual, 
                      COALESCE(SUM(d.subtotal), 0) AS total_nilai', false)
            // LEFT JOIN: produk yang belum terjual tetap tampil.
            ->join('detil_penjualan d', 'd.kode_produk = pr.kode_produk', 'left')
            // Satu baris per produk.
            ->groupBy('pr.kode_produk, pr.nama_produk')
            ->orderBy('total_unit_terjual', 'DESC')
            ->orderBy('pr.nama_produk', 'ASC')
            ->get()->getResultArray();
    }
}