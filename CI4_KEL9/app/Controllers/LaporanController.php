<?php

namespace App\Controllers;

use App\Models\LaporanModel;

class LaporanController extends BaseController
{
    protected LaporanModel $laporan;

    public function __construct()
    {
        $this->laporan = new LaporanModel();
    }

    public function penjualan()
    {
        $rows = $this->laporan->daftarPenjualan();
        return view('laporan/penjualan', [
            'title' => 'Daftar Transaksi Penjualan (JOIN)',
            'rows'  => $rows,
            'sql'   => (string) db_connect()->getLastQuery(),
        ]);
    }

    public function pelanggan()
    {
        $rows = $this->laporan->rekapPelanggan();
        return view('laporan/pelanggan', [
            'title' => 'Rekap Penjualan per Pelanggan (Agregasi)',
            'rows'  => $rows,
            'sql'   => (string) db_connect()->getLastQuery(),
        ]);
    }

    public function produk()
    {
        $rows = $this->laporan->rekapProduk();
        return view('laporan/produk', [
            'title' => 'Laporan Penjualan per Produk (JOIN + Agregasi)',
            'rows'  => $rows,
            'sql'   => (string) db_connect()->getLastQuery(),
        ]);
    }
}