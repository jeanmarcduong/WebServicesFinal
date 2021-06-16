<?php
//
//* Author:
//"Isabel Lopez"
//* Date:12 / 4 / 2020
//* File:


namespace Bakery\Authentication;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Bakery\Models\Token;


class BearerAuthenticator
{

    public function __invoke(Request $request, Response $response, $next)
    {
// If the header named "Authorization" does not exist, display an
//        error
if (!$request->hasHeader('Authorization')) {

    $results = array('status' => 'Authorization header not tu available');

    return $response->withJson($results, 404, JSON_PRETTY_PRINT);
}
// ChatterAPI-Authorization header exists, retrieve the header and
//the header value
$auth = $request->getHeader('Authorization');
/* The value of the authorization header consists of Bearer and the
key separated
* by a space.
*/
//Remember, the request header in HTTP is still Authorization. It doesn't change.
//5. Now, let's wire the new Bearer Authenticator middleware in the app.
//    First, let's have a thinking.
//We want to users provide their username and password to validate. If it's successful, we
//allow them to access the protected resource in the server.
//    Let's assume all Messages, Comments are protected for now. We will allow only
//authenticated users to visit these resources.
//So, we have to make a small change in our routes.php file.
//Let's synchronize this file now.
    $token = substr($auth[0], strpos($auth[0], ' ') + 1); // the key is
//the second part of the string after a space
if (!Token::validateBearer($token)) {
    $results = array("status" => "Authentication failed");
    return $response->withJson($results, 401, JSON_PRETTY_PRINT);
}
// A user has been authenticated.
$response = $next($request, $response);
return $response;
}
















}