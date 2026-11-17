<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\ProdukModel;

// Fitur C.5. Controller: validasi & hitung total. Model (simpanTransaksi): menjalankan transaksi database.
class TransaksiController extends BaseController
{
    protected PenjualanModel $penjualan;

    public function __construct()
    {
        $this->penjualan = new PenjualanModel();
    }

    public function tambah()
    {
        return view('transaksi/tambah', [
            'title'     => 'Input Transaksi Penjualan',
            'pelanggan' => $this->penjualan->daftarPelanggan(),
            'produk'    => (new ProdukModel())->orderBy('kode_produk', 'ASC')->findAll(),
        ]);
    }

    public function simpan()
    {
        // Validasi header
        $rules = [
            'no_penjualan'   => 'required|max_length[15]',
            'tgl_penjualan'  => 'required|valid_date[Y-m-d]',
            'kode_pelanggan' => 'required|max_length[10]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Kumpulkan baris detail (baris kosong dilewati)
        $detail = [];
        $total  = 0;

        // Ambil baris detail dari form (baris kosong dilewati, baris salah ditolak).
        foreach ($this->request->getPost('detail') ?? [] as $b) {
            $kode   = trim($b['kode_produk'] ?? '');
            $jumlah = trim($b['jumlah'] ?? '');
            $harga  = trim($b['harga'] ?? '');

            if ($kode === '' && $jumlah === '' && $harga === '') {
                continue;
            }
            if ($kode === '' || ! ctype_digit($jumlah) || (int) $jumlah <= 0
                || ! is_numeric($harga) || (float) $harga < 0) {
                return redirect()->back()->withInput()->with('errors', [
                    'Setiap baris detail harus berisi kode produk, jumlah (> 0), dan harga (>= 0).',
                ]);
            }

            // Subtotal & total dihitung di server.
            $subtotal = (int) $jumlah * (float) $harga;
            $total   += $subtotal;
            $detail[] = [
                'kode_produk' => $kode,
                'jumlah'      => (int) $jumlah,
                'harga'       => (float) $harga,
                'subtotal'    => $subtotal,
            ];
        }

        // Minimal 2 detail. Dicek sebelum transaksi, jadi ini validasi (bukan rollback).
        if (count($detail) < 2) {
            return redirect()->back()->withInput()
                ->with('errors', ['Transaksi harus memiliki minimal dua baris detail.']);
        }

        $header = [
            'no_penjualan'   => trim($this->request->getPost('no_penjualan')),
            'tgl_penjualan'  => $this->request->getPost('tgl_penjualan'),
            'kode_pelanggan' => $this->request->getPost('kode_pelanggan'),
            'total'          => $total,
            'keterangan'     => trim($this->request->getPost('keterangan') ?? ''),
        ];

        try {
            // Simpan header + detail dalam 1 transaksi. Gagal -> Model ROLLBACK lalu melempar exception.
            $this->penjualan->simpanTransaksi($header, $detail);
        } catch (\Throwable $e) {
            // Gagal: tampilkan penyebab dari PostgreSQL.
            return redirect()->back()->withInput()->with('galat',
                'Transaksi GAGAL dan di-ROLLBACK. Tidak ada data yang tersimpan. Penyebab: ' . $e->getMessage());
        }

        // Berhasil (sudah COMMIT).
        return redirect()->to('/laporan/penjualan')
            ->with('pesan', 'Transaksi ' . $header['no_penjualan'] . ' berhasil disimpan (COMMIT).');
    }
}