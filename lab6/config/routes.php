<?php
/**
 * Author: Jeanmarc Duong
 * Date: 12/1/2020
 * File: routes.php
 * Description:
 */



use Bakery\Middleware\Logging as ChatterLogging;
use Bakery\Authentication\MyAuthenticator;
use Bakery\Authentication\BasicAuthenticator;
use Bakery\Authentication\BearerAuthenticator;
use Bakery\Authentication\JWTAuthenticator;

$app->get('/', function ($request, $response, $args) {
    return $response->write('Welcome to the Bakery API!');
});

$app->get('/hello/{name}', function ($request, $response, $args) {
    return $response->write("Hello " . $args['name']);
});


//Employee routes 1
$app->group('/employees', function () {
    $this->get('', 'EmployeeController:index');
    $this->get('/{id}', 'EmployeeController:view');
    $this->get('/{id}/details', 'EmployeeController:viewDetailsEmployee');
    $this->post('', 'EmployeeController:create');
    $this->put('/{id}', 'EmployeeController:update');
    $this->patch('/{id}', 'EmployeeController:update');
    $this->delete('/{id}', 'EmployeeController:delete');

    $this->post('/authBearer', 'EmployeeController:authBearer');
    $this->post('/authJWT', 'EmployeeController:authJWT');

});

$app->group('', function () {

//Employee Detail routes 2
    $this->group('/details', function () {
        $this->get('', 'EmployeeDetailsController:index');
        $this->get('/{id}', 'EmployeeDetailsController:view');

        $this->post('', 'EmployeeDetailsController:create');
        $this->put('/{id}', 'EmployeeDetailsController:update');
        $this->patch('/{id}', 'EmployeeDetailsController:update');
        $this->delete('/{id}', 'EmployeeDetailsController:delete');


    });

//Menu routes 3
    $this->group('/menu', function () {
        $this->get('', 'MenuController:index');
        $this->get('/{id}', 'MenuController:view');
        $this->get('/{id}/orders', 'MenuController:viewMenuOrder');

        $this->post('', 'MenuController:create');
        $this->put('/{id}', 'MenuController:update');
        $this->patch('/{id}', 'MenuController:update');
        $this->delete('/{id}', 'MenuController:delete');
    });


//Orders routes 4
    $this->group('/orders', function () {
        $this->get('', 'OrdersController:index');
        $this->get('/{id}', 'OrdersController:view');

        $this->post('', 'OrdersController:create');
        $this->put('/{id}', 'OrdersController:update');
        $this->patch('/{id}', 'OrdersController:update');
        $this->delete('/{id}', 'OrdersController:delete');
    });

//Store Location routes 5
    $this->group('/locations', function () {
        $this->get('', 'StoreLocationController:index');
        $this->get('/{id}', 'StoreLocationController:view');

        $this->post('', 'StoreLocationController:create');
        $this->put('/{id}', 'StoreLocationController:update');
        $this->patch('/{id}', 'StoreLocationController:update');
        $this->delete('/{id}', 'StoreLocationController:delete');
    });



//})->add(new MyAuthenticator());
})->add(new JWTAuthenticator());
//})->add(new BearerAuthenticator());
//})->add(new BasicAuthenticator());
$app->add(new ChatterLogging());
//})->add(new MyAuthenticator());
$app->run();