<?php
/**
 * Author: Jeanmarc Duong
 * Date: 12/1/2020
 * File: services.php
 * Description:
 */

// Alias to the controllers
use Bakery\Controllers\EmployeeController as EmployeeController;
use Bakery\Controllers\EmployeeDetailsController as EmployeeDetailsController;
use Bakery\Controllers\MenuController as MenuController;
use Bakery\Controllers\OrdersController as OrdersController;
use Bakery\Controllers\StoreLocationController as StoreLocationController;

/*
 * The following is the controller and middleware factory. It
 * registers controllers and middleware with the DI container so
 * they can be accessed in other classes. Injecting instances into
 * the DI container so you don't need to pass the entire container or app,
 * rather only the services needed.
 * https://akrabat.com/accessing-services-in-slim-3/#comment-35429
 */
// Register controllers with the DIC. $c is the container itself.
$container['EmployeeController'] = function ($c) {
    return new EmployeeController();
};

$container['EmployeeDetailsController'] = function ($c) {
    return new EmployeeDetailsController();
};

$container['MenuController'] = function ($c) {
    return new MenuController();
};

$container['OrdersController'] = function ($c) {
    return new OrdersController();
};

$container['StoreLocationController'] = function ($c) {
    return new StoreLocationController();
};




