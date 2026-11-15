<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'ProdukController::index');
$routes->get('produk', 'ProdukController::index');
$routes->get('produk/create', 'ProdukController::create');
$routes->post('produk/store', 'ProdukController::store');
$routes->get('produk/edit/(:segment)', 'ProdukController::edit/$1');
$routes->post('produk/update/(:segment)', 'ProdukController::update/$1');
$routes->get('produk/delete/(:segment)', 'ProdukController::delete/$1');
$routes->get('laporan/penjualan', 'LaporanController::penjualan');
$routes->get('laporan/pelanggan', 'LaporanController::pelanggan');
$routes->get('laporan/produk',    'LaporanController::produk');
$routes->get('transaksi/tambah', 'TransaksiController::tambah');
$routes->post('transaksi/simpan', 'TransaksiController::simpan');