<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
// Peta URL -> Controller::method. get = tampil halaman, post = terima form, (:segment) = parameter URL.
$routes->get('/', 'ProdukController::index');
$routes->get('produk', 'ProdukController::index');
$routes->get('produk/create', 'ProdukController::create');
$routes->post('produk/store', 'ProdukController::store');
$routes->get('produk/edit/(:segment)', 'ProdukController::edit/$1');
$routes->post('produk/update/(:segment)', 'ProdukController::update/$1');
// Hapus via GET agar sederhana (idealnya POST/DELETE).
$routes->get('produk/delete/(:segment)', 'ProdukController::delete/$1');
// Laporan: JOIN, agregasi, per produk.
$routes->get('laporan/penjualan', 'LaporanController::penjualan');
$routes->get('laporan/pelanggan', 'LaporanController::pelanggan');
$routes->get('laporan/produk',    'LaporanController::produk');
// Transaksi penjualan (header + detail).
$routes->get('transaksi/tambah', 'TransaksiController::tambah');
$routes->post('transaksi/simpan', 'TransaksiController::simpan');