<?php

namespace App\Controllers;

use App\Models\ProdukModel;

class ProdukController extends BaseController
{
    protected $produkModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
    }

    // 1. Menampilkan daftar produk & pencarian
    public function index()
    {
        $keyword = $this->request->getVar('keyword');
        
        if ($keyword) {
            $produk = $this->produkModel->cariProduk($keyword)->findAll();
        } else {
            $produk = $this->produkModel->findAll();
        }

        $data = [
            'title' => 'Data Produk (CodeIgniter 4)',
            'produk' => $produk,
            'keyword' => $keyword
        ];

        return view('produk/index', $data);
    }

    // 2. Form Tambah Produk
    public function create()
    {
        return view('produk/tambah');
    }

    // Proses Simpan Data Tambahan
    public function store()
    {
        $this->produkModel->save([
            'kode_produk' => $this->request->getVar('kode_produk'),
            'nama_produk' => $this->request->getVar('nama_produk'),
            'harga_jual'  => $this->request->getVar('harga_jual'),
            'stok'        => $this->request->getVar('stok'),
        ]);

        return redirect()->to('/produk')->with('pesan', 'Data produk berhasil ditambahkan.');
    }

    // 3. Form Edit Produk
    public function edit($id)
    {
        $data = [
            'title' => 'Edit Produk',
            'produk' => $this->produkModel->find($id)
        ];

        return view('produk/edit', $data);
    }

    // Proses Update Data
    public function update($id)
    {
        $this->produkModel->update($id, [
            'nama_produk' => $this->request->getVar('nama_produk'),
            'harga_jual'  => $this->request->getVar('harga_jual'),
            'stok'        => $this->request->getVar('stok'),
        ]);

        return redirect()->to('/produk')->with('pesan', 'Data produk berhasil diperbarui.');
    }

    // 4. Hapus Produk
    public function delete($id)
    {
        $this->produkModel->delete($id);
        return redirect()->to('/produk')->with('pesan', 'Data produk berhasil dihapus.');
    }
}