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