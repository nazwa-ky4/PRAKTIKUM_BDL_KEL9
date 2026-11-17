<?php

namespace App\Models;

use CodeIgniter\Model;

// Model penjualan + detil_penjualan. Berisi transaksi database (C.5).
class PenjualanModel extends Model
{
    protected $table            = 'penjualan';
    protected $primaryKey       = 'no_penjualan';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';

    // Daftar pelanggan untuk pilihan di form
    public function daftarPelanggan(): array
    {
        return $this->db->table('pelanggan')
            ->orderBy('nama_pelanggan', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Menyimpan 1 header + banyak detail dalam SATU transaksi database.
     * Jika salah satu insert gagal, semua dibatalkan (ROLLBACK)
     * dan exception dilempar ke Controller.
     */
    public function simpanTransaksi(array $header, array $detail): void
    {
        $db = $this->db;

        // 1. Mulai transaksi
        // Mode manual: transBegin -> transCommit / transRollback.
        $db->transBegin();

        try {
            // 2. Simpan header penjualan
            $this->cekHasil($db->table('penjualan')->insert($header));

            // 3. Simpan seluruh detail penjualan
            // 1 INSERT per detail. Kode produk tidak ada (mis. P999) -> foreign key error -> catch -> ROLLBACK.
            foreach ($detail as $i => $d) {
                $this->cekHasil($db->table('detil_penjualan')->insert([
                    'no_penjualan' => $header['no_penjualan'],
                    'no_urut'      => $i + 1,
                    'kode_produk'  => $d['kode_produk'],
                    'jumlah'       => $d['jumlah'],
                    'harga'        => $d['harga'],
                    'subtotal'     => $d['subtotal'],
                ]));
            }

            // 4. Semua berhasil -> COMMIT
            $db->transCommit();
        } catch (\Throwable $e) {
            // 5. Ada yang gagal -> ROLLBACK, lalu teruskan error
            // Batalkan semua INSERT (header + detail yang sempat masuk).
            $db->transRollback();
            throw $e;
        }
    }

    // Jika DBDebug dimatikan, insert yang gagal mengembalikan false, bukan exception
    private function cekHasil($hasil): void
    {
        if ($hasil === false) {
            $err = $this->db->error();
            throw new \RuntimeException($err['message'] ?: 'Query gagal dijalankan.');
        }
    }
}