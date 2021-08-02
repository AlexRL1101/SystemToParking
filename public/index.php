<?php

ini_set('display_errors', 1);
ini_set('display_starup_error', 1);
error_reporting(E_ALL);
/*Requerimos una sola vez el autoload 
que es como un buscador de google*/
require_once '../vendor/autoload.php';

session_start();

// $dotenv  = new Dotenv\Dotenv( __DIR__ . '/..'); 
// $dotenv -> load();
//Son como librerias, como include de C
use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;
use Zend\Diactoros\Response\RedirectResponse;
\Stripe\Stripe::setApiKey('sk_test_ZTGqyrfjNBnIGJjFn17f4cYg00BsOKXonO');
//Instanciamos una capsula de datos
$capsule = new Capsule;
//Datos y Conexion a BD
$capsule->addConnection([
  // 'driver'    => getenv('DB_DRIVER'),
  // 'host'      => getenv('DB_HOST'),
  // 'database'  => getenv('DB_NAME'),
  // 'username'  => getenv('DB_USER'),
  // 'password'  => getenv('DB_PASS'),
  // 'charset'   => 'utf8',
  // 'collation' => 'utf8_unicode_ci',
  // 'prefix'    => '',
  // 'port'      => getenv('DB_PORT')
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'bd_hotel',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
    'port'      => getenv('DB_PORT'),
]);
// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();
// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
  $_SERVER,
  $_GET,
  $_POST,
  $_COOKIE,
  $_FILES
);
//creamos una instancia de rutas
$routeContainer = new RouterContainer();
//Variable para mapear las rutas
$map = $routeContainer->getMap();
//Creamos todas las rutas del sistema para mayor proteccion
$map->get('home', '/', [
  'controller' => 'App\Controllers\IndexController',
  'action' => 'indexAction'
]);
$map->get('account', '/account', [
  'controller' => 'App\Controllers\AccountController',
  'action' => 'accountAction',
  'auth' => true
]);
$map->get('addVehicle', '/vehicle/add', [
  'controller' => 'App\Controllers\VehicleController',
  'action' => 'startVehicleAction',
  'auth' => true
]);
$map->get('history', '/history', [
  'controller' => 'App\Controllers\HistoryController',
  'action' => 'historyAction',
  'auth' => true
]);
$map->get('registro', '/registry', [
  'controller' => 'App\Controllers\RegistryController',
  'action' => 'startRegistryAction'
]);
$map->get('reservacion', '/reservation', [
  'controller' => 'App\Controllers\ReservationController',
  'action' => 'startReservation',
  'auth' => true
]);
$map->post('saveVehicle', '/save/vehicle', [
  'controller' => 'App\Controllers\VehicleController',
  'action' => 'getAddVehicleAction',
  'auth' => true
]);
$map->post('saveUser', '/registry', [
  'controller' => 'App\Controllers\RegistryController',
  'action' => 'getAddRegistryAction'
]);
$map->post('saveReservation', '/save', [
  'controller' => 'App\Controllers\ReservationController',
  'action' => 'postAddReservationAction',
  'auth' => true
]);
$map->post('authUser', '/auth', [
  'controller' => 'App\Controllers\IndexController',
  'action' => 'postLogin'
]);
$map->get('logout', '/logout', [
  'controller' => 'App\Controllers\IndexController',
  'action' => 'getLogout',
  'auth' => true
]);
$map->post('BoxFirst', '/select/box', [
  'controller' => 'App\Controllers\SelectBoxController',
  'action' => 'postAddBox',
  'auth' => true
]);
$map->post('UpdatedProfile', '/profile/update', [
  'controller' => 'App\Controllers\AccountController',
  'action' => 'postUpdatedProfile',
  'auth' => true
]);
$map->post('newProfile', '/new/data', [
  'controller' => 'App\Controllers\AccountController',
  'action' => 'postNewProfile',
  'auth' => true
]);
$map->post('ChargePayment', '/charge/pay', [
  'controller' => 'App\Controllers\PaymentController',
  'action' => 'postChargePayment',
  'auth' => true
]);
//Iniciamos Buacador de rutas
$matcher = $routeContainer->getMatcher();
//Recojemos las rutas del Browser
$route = $matcher->match($request);
//Si la ruta no se encuentra en el mapa
if (!$route) {
  echo 'No route';
} else {
  $hadlerData = $route->handler;
  $controllerName = $hadlerData['controller'];
  $actionName = $hadlerData['action'];
  $needsAuth = $hadlerData['auth'] ?? false;

  if ($needsAuth && !isset($_SESSION['userId'])) {
    $response = new RedirectResponse('/');
  } else {
    $controller = new $controllerName;
    $response = $controller->$actionName($request);
  }
  
  foreach ($response -> getHeaders() as $name => $values) {
    foreach ($values as $value) {
      header(sprintf('%s: %s', $name, $value), false);
    }
  }
  http_response_code($response->getStatusCode());
  echo $response->getBody();
}
