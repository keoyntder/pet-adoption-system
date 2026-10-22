
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default homepage
$routes->get('/', 'Auth::login');

// Authentication
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::processRegister');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::processLogin');

$routes->get('logout', 'Auth::logout');

// Adopter
$routes->get('pets', 'Pets::index');
$routes->get('pets/(:num)', 'Pets::show/$1');
$routes->post('pets/apply/(:num)', 'Pets::apply/$1');
$routes->get('my-applications', 'Applications::index');


// Admin dashboard
$routes->get('admin/dashboard', 'Admin\Dashboard::index');

// Admin pet management
$routes->group('admin/pets', static function ($routes) {
    $routes->get('/', 'Admin\PetManagement::index');
    $routes->get('create', 'Admin\PetManagement::create');
    $routes->post('store', 'Admin\PetManagement::store');

    $routes->get('edit/(:num)', 'Admin\PetManagement::edit/$1');
    $routes->post('update/(:num)', 'Admin\PetManagement::update/$1');
    $routes->post('delete/(:num)', 'Admin\PetManagement::delete/$1');

         


});
$routes->get('admin/applications', 'Admin\Applications::index');
$routes->post('admin/applications/update/(:num)', 'Admin\Applications::update/$1');
