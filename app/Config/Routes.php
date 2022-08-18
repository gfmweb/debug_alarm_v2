<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */
$routes->post('/hook','TELEGRAM\TelegramHooks::index'); //Роут телеграм хуков

$routes->get('/telegram_app','TELEGRAM\TelegramView::index'); // Роут телеграм приложения

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/test','Test::index');
$routes->get('/test_telega','Test::telega');
$routes->get('/admin','ADMIN\Admin::index');


$routes->get('/finishRegister/(:any)','Login::RegisterNewUser'); // Редирект на бота недопользователя

$routes->get('/login','Login::index');
$routes->get('/CreateAdmin','Login::FirstAdminCreate');
$routes->post('/login/getLoginForm','Login::requestLoginForm');
$routes->post('/login/admin','Login::adminLogPas');
$routes->post('/login/user','Login::userLogPas');
$routes->post('/login/check','Login::checkCode');
$routes->get('/logout','Login::logOut');

$routes->get('/admin/init','ADMIN\Admin::init',['filter'=>'AdminAuth']);
$routes->get('/admin/projects','ADMIN\Admin::getProjects',['filter'=>'AdminAuth']);
$routes->get('/admin/users','ADMIN\Admin::getUsers',['filter'=>'AdminAuth']);
$routes->get('/admin/admins','ADMIN\Admin::getAdmins',['filter'=>'AdminAuth']);
$routes->get('/admin/settings','ADMIN\Admin::getSettings',['filter'=>'AdminAuth']);

$routes->post('/admin/getProjectByID','ADMIN\Admin::getProjectByID',['filter'=>'AdminAuth']);
$routes->post('/admin/deleteProject','ADMIN\Admin::deleteProject',['filter'=>'AdminAuth']);
$routes->post('/admin/createProject','ADMIN\Admin::createProject',['filter'=>'AdminAuth']);
$routes->post('/admin/updateProject','ADMIN\Admin::updateProject',['filter'=>'AdminAuth']);


$routes->post('/admin/createUser','ADMIN\Admin::createUser',['filter'=>'AdminAuth']);
$routes->post('/admin/deleteUser','ADMIN\Admin::deleteUser',['filter'=>'AdminAuth']);

$routes->get('/admin/getServiceStatus','ADMIN\Admin::getServiceStatus',['filter'=>'AdminAuth']);
$routes->get('/admin/getHookAddress','ADMIN\Admin::getHookAddress',['filter'=>'AdminAuth']);
$routes->post('/admin/changeServiceMode','ADMIN\Admin::changeServiceMode',['filter'=>'AdminAuth']);
$routes->post('/admin/setNewPassword','ADMIN\Admin::setNewPassword',['filter'=>'AdminAuth']);
$routes->post('/admin/setWebHook','ADMIN\Admin::setWebHook',['filter'=>'AdminAuth']);


$routes->get('/user','USER\User::index');
$routes->get('/user/mainMenu','USER\User::getMainMenu',['filter'=>'UserAuth']);
$routes->get('/telegram/mainMenu','TELEGRAM\TelegramView::getMainMenu',['filter'=>'UserAuth']);
$routes->get('/user/getLastLogs','USER\User::getLastLogs',['filter'=>'UserAuth']);
$routes->get('/user/getLogInfoByID','USER\User::getLogInfoByID',['filter'=>'UserAuth']);
$routes->post('/user/sendAlarm','USER\User::sendAlarm',['filter'=>'UserAuth']);
$routes->post('/user/setNewPassword','USER\User::setNewPassword',['filter'=>'UserAuth']);
$routes->post('/user/LogDBQuery','USER\User::LogDBQuery',['filter'=>'UserAuth']);

$routes->post('/loginByTelegram','TELEGRAM\TelegramView::login');

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
