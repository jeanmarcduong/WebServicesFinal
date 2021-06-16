<?php
//
//* Author:
//"Isabel Lopez"
//* Date:12 / 3 / 2020
//*


namespace Bakery\Authentication;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Bakery\Models\Employee;

class MyAuthenticator
{

    public function __invoke(Request $request, Response $response, $next)
    {
//// If the header named "ChatterAPI-Authorization" does not exist,
//        display an error
        if (!$request->hasHeader('Bakery-Authorization')) {

            $results = array('status' => 'Authorization header not available');
            return $response->withJson($results, 404, JSON_PRETTY_PRINT);
        }
//// ChatterAPI-Authorization header exists, retrieve the username and
//        password from the header
        $auth = $request->getHeader('Bakery-Authorization');
        list($owner, $password) = explode(':', $auth[0]);
//// Validate the header value by calling User's authenticateUser
//method.
        if (!Employee::authenticateOwner($owner, $password)) {
            $results = array("status" => "Authentication failed");
            return $response->withJson($results, 401, JSON_PRETTY_PRINT);
        }
// A user has been authenticated.
        $response = $next($request, $response);
        return $response;
    }


}