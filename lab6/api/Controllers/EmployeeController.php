<?php
/**
 * Author: Jeanmarc Duong
 * Date: 12/1/2020
 * File: EmployeeController.php
 * Description:
 */

namespace Bakery\Controllers;

use Chatter\Models\User;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Bakery\Models\Employee;
use Bakery\Validations\Validator;
use Bakery\Models\Token;



class EmployeeController
{
    //list all employees with pagination, sort, search by query features
    public function index(Request $request, Response $response, array $args)
    {
        $results = Employee::getEmployees($request);
        $code = array_key_exists("status", $results) ? 500 : 200;

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    //view an employee by id
    public function view(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        $results = Employee::getEmployeeById($id);
        $code = array_key_exists("status", $results) ? 500 : 200;

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    //view the details of an employee
    public function viewDetailsEmployee(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        $results = Employee::getDetailsByEmployee($id);
        $code = array_key_exists("status", $results) ? 500 : 200;

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    // Create a employee when the employee registers
    public function create(Request $request, Response $response, array $args)
    {
// Validate the request
        $validation = Validator::validateEmployee($request);
// If validation failed
        if (!$validation) {
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors()
            ];
            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }
// Validation has passed; Proceed to create the employee
        $employee = Employee::createEmployee($request);
        $results = [
            'status' => 'employee created',
            'data' => $employee
        ];
        return $response->withJson($results, 201, JSON_PRETTY_PRINT);
    }

    // Update an employee
    public function update(Request $request, Response $response, array $args)
    {
        $employee = Employee::updateEmployee($request);
        $results = [
            'status' => 'user updated',
            'data' => $employee
        ];
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    // Delete an employee
    public function delete(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        Employee::deleteEmployee($id);
        $results = [
            'status' => 'Employee deleted',
        ];
        $code = array_key_exists('status', $results) ? 200 : 500;
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);

    }

// Validate a user with username and password. It returns a Bearer token on
//success
    public function authBearer(Request $request, Response $response) // where is it being used
    {
        $params = $request->getParsedBody();
        $username = $params['username'];
        $password = $params['password'];

        $user = Employee::authenticateEmployee($username, $password);
        if ($user) {
            $status_code = 200;
            $token = Token::generateBearer($user->id);
            $results = [
                'status' => 'login successful',
                'token' => $token
            ];
        } else {
            $status_code = 401;
            $results = [
                'status' => 'login failed'
            ];
        }
        return $response->withJson($results, $status_code,
            JSON_PRETTY_PRINT);
    }

// Validate a user with username and password. It returns a JWT token on
//success.
    public function authJWT(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $username = $params['username'];
        $password = $params['password'];
        $user = Employee::authenticateEmployee($username, $password);
        if ($user) {
            $status_code = 200;
            $jwt = Employee::generateJWT($user->id);
            $results = [
                'status' => 'login successful',
                'jwt' => $jwt,
                'name' => $user->username
            ];
        } else {
            $status_code = 401;
            $results = [
                'status' => 'login failed',
            ];
        }
//return $results;
        return $response->withJson($results, $status_code,
            JSON_PRETTY_PRINT);
    }



}