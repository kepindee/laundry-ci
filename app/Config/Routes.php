<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('login', 'AuthController::login'); 
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

#$routes->ge('produk', 'ProdukController::create', ['filter' => 'auth']);
#$routes->post('produk', 'ProdukController::create', ['filter' => 'auth']);
#$routes->post('produk/edit/(:any)', 'ProdukController::edit/$1', ['filter' => 'auth']);
#$routes->get('produk/delete/(:any)', 'ProdukController::delete/$1', ['filter' => 'auth']);

#CRUD Produk group
$routes->group('produk', ['filter' => 'auth'], function ($routes) { 
    $routes->get('', 'ProdukController::index');
    $routes->get('download', 'ProdukController::download');
    $routes->post('', 'ProdukController::create');
    $routes->post('edit/(:any)', 'ProdukController::edit/$1');
    $routes->get('delete/(:any)', 'ProdukController::delete/$1');
});

$routes->group('keranjang', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'TransaksiController::index');
    $routes->post('', 'TransaksiController::cart_add');
    $routes->post('edit', 'TransaksiController::cart_edit');
    $routes->get('delete/(:any)', 'TransaksiController::cart_delete/$1');
    $routes->get('clear', 'TransaksiController::cart_clear');
});

$routes->group('transaksi', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'TransaksiController::list_transaksi');
    $routes->post('checkout', 'TransaksiController::checkout');
    $routes->post('update-status/(:num)', 'TransaksiController::update_status/$1');
    $routes->get('delete/(:num)', 'TransaksiController::delete_transaksi/$1');
});