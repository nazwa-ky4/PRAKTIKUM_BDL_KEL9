<?php

namespace App\Controllers;

use App\Models\ProdukModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class ProdukController extends BaseController
{
    protected ProdukModel $produkModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
    }

    // Aturan validasi dipakai bersama oleh store() dan update()
    private function rules(bool $untukTambah): array
    {
        $rules = [
            'nama_produk' => 'required|max_length[100]',
            'satuan'      => 'required|max_length[20]',
            'harga_jual'  => 'required|numeric|greater_than_equal_to[0]',
            'harga_beli'  => 'required|numeric|greater_than_equal_to[0]',
            'stok'        => 'required|integer|greater_than_equal_to[0]',
        ];

        if ($untukTambah) {
            $rules['kode_produk'] = 'required|max_length[10]|is_unique[produk.kode_produk]';
        }

        return $rules;
    }

    // Tampil + cari
    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $produk = $this->produkModel
            ->cariProduk($keyword)
            ->orderBy('kode_produk', 'ASC')
            ->findAll();

        return view('produk/index', [
            'title'   => 'Data Produk (CodeIgniter 4)',
            'produk'  => $produk,
            'keyword' => $keyword,
        ]);
    }

    // Form tambah
    public function create()
    {
        return view('produk/tambah', ['title' => 'Tambah Produk']);
    }

    // Simpan produk baru
    public function store()
    {
        if (! $this->validate($this->rules(true))) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->produkModel->insert([
            'kode_produk' => trim($this->request->getPost('kode_produk')),
            'nama_produk' => trim($this->request->getPost('nama_produk')),
            'satuan'      => trim($this->request->getPost('satuan')),
            'harga_jual'  => $this->request->getPost('harga_jual'),
            'harga_beli'  => $this->request->getPost('harga_beli'),
            'stok'        => $this->request->getPost('stok'),
            'deskripsi'   => trim($this->request->getPost('deskripsi') ?? ''),
        ]);

        return redirect()->to('/produk')->with('pesan', 'Data produk berhasil ditambahkan.');
    }

    // Form edit
    public function edit($id)
    {
        $produk = $this->produkModel->find($id);

        if (! $produk) {
            return redirect()->to('/produk')->with('galat', 'Produk tidak ditemukan.');
        }

        return view('produk/edit', ['title' => 'Edit Produk', 'produk' => $produk]);
    }

    // Simpan perubahan
    public function update($id)
    {
        if (! $this->validate($this->rules(false))) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->produkModel->update($id, [
            'nama_produk' => trim($this->request->getPost('nama_produk')),
            'satuan'      => trim($this->request->getPost('satuan')),
            'harga_jual'  => $this->request->getPost('harga_jual'),
            'harga_beli'  => $this->request->getPost('harga_beli'),
            'stok'        => $this->request->getPost('stok'),
            'deskripsi'   => trim($this->request->getPost('deskripsi') ?? ''),
        ]);

        return redirect()->to('/produk')->with('pesan', 'Data produk berhasil diperbarui.');
    }

    // Hapus produk
    public function delete($id)
    {
        try {
            $this->produkModel->delete($id);
            return redirect()->to('/produk')->with('pesan', 'Data produk berhasil dihapus.');
        } catch (DatabaseException $e) {
            // Terjadi jika produk sudah dipakai di detil_penjualan / detil_pembelian
            return redirect()->to('/produk')
                ->with('galat', 'Gagal menghapus: produk sedang dipakai dalam transaksi.');
        }
    }
}