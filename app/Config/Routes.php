<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Index');
$routes->setDefaultMethod('main');
$routes->setTranslateURIDashes(false);
$routes->set404Override('App\Controllers\Index::response404');
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->post('/', 'Index::index');
$routes->get('/', 'Index::main');
$routes->get('/logoutPage', 'Index::main', ['as' => 'logoutPage']);

$routes->post('access/check', 'Access::check');
$routes->get('access/logout/(:any)', 'Access::logout/$1');
$routes->get('access/captcha/(:any)', 'Access::captcha/$1');

$routes->get('databaseTool/migrate', 'DatabaseTool::migrate', ['filter' => 'databaseTool']);
$routes->get('databaseTool/rollback', 'DatabaseTool::rollback', ['filter' => 'databaseTool']);
$routes->get('databaseTool/seed/(:any)', 'DatabaseTool::seed/$1', ['filter' => 'databaseTool']);

$routes->group('access', ['filter' => 'auth:mustNotBeLoggedIn'], function($routes) {
    $routes->post('submitEmailPhoneNumber', 'Access::loginSubmitEmailPhoneNumber', ['filter' => 'auth:mustNotBeLoggedIn']);
    $routes->post('submitOTP', 'Access::loginSubmitOTP', ['filter' => 'auth:mustNotBeLoggedIn']);
});

$routes->group('register', ['filter' => 'auth:mustNotBeLoggedIn'], function($routes) {
    $routes->post('submitData', 'Access::registerSubmitData', ['filter' => 'auth:mustNotBeLoggedIn']);
    $routes->post('submitOTP', 'Access::registerSubmitOTP', ['filter' => 'auth:mustNotBeLoggedIn']);
});

$routes->group('access', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
    $functionRoute =   'Access';
    $routes->post('getDataOption', $functionRoute.'::getDataOption');
    $routes->post('getDataOptionByKey/(:any)/(:any)/(:any)', $functionRoute.'::getDataOptionByKey/$1/$2/$3');
    $routes->post('detailProfileSetting', $functionRoute.'::detailProfileSetting');
    $routes->post('saveDetailProfileSetting', $functionRoute.'::saveDetailProfileSetting');
});

$routes->group('assets', [], function($routes) {
    $routes->get('logoMerk/(:any)', 'Assets::logoMerk/$1');
    $routes->get('logoMarketplace/(:any)', 'Assets::logoMarketplace/$1');
    $routes->get('photoBarang/(:any)', 'Assets::photoBarang/$1');
    $routes->get('customerAvatar/(:any)', 'Assets::customerAvatar/$1');
    $routes->get('customerSlideBanner/(:any)', 'Assets::customerSlideBanner/$1');
});

$routes->group('dashboard', ['filter' => 'auth:allowNotLoggedIn'], function($routes) {
    $functionRoute =   'Dashboard';
    $routes->post('getDataDashboard', $functionRoute.'::getDataDashboard');
});
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
