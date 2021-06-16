<?php

//* Author:
//"Isabel Lopez"
//* Date:12 / 4 / 2020
//* File:JWTAuthenticator


namespace Bakery\Authentication;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Bakery\Models\Employee;


class JWTAuthenticator
{

    public function __invoke(Request $request, Response $response, $next)
    {
// If the header named "Authorization" does not exist, display an
//        error
if (!$request->hasHeader('Authorization')) {
    $results = array('status' => 'Authorization header not JWT available');
    return $response->withJson($results, 404, JSON_PRETTY_PRINT);
}
// If Authorization header exists, retrieve the header and the
//header value
$auth = $request->getHeader('Authorization');
/* The value of the authorization header consists of Bear and the
key separated
6. Now, let's wire the new middleware in the app.
Open the routes.php.
At the beginning of the file, add the line.
At the almost end of the file, we add line and the code should like this.
We use JWT Authenticator to protect messages and comments resource.
And in the middle of the routes.php file, in the group of users routes, we add a new route for
users endpoint
The new route is POST /users/authJWT
7. Now, let's have a test.
First, we have to ask user to provide username and password to either generate a new JWT
token.
The request is POST /users/authJWT
For better test, please use No Auth option in the tab of Authorization.
And in the Body tab, provide your username and password.
* by a space.
*/
$token = substr($auth[0], strpos($auth[0], ' ') + 1); // the key is
//the second part of the string after a space
if (!Employee::validateJWT($token)) {
    $results = array("status" => "Authentication failed");
    return $response->withJson($results, 401, JSON_PRETTY_PRINT);
}
// A user has been authenticated.
$response = $next($request, $response);
return $response;
}









}