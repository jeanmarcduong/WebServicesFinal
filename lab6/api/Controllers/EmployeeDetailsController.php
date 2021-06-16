<?php
/**
 * Author: Jeanmarc Duong
 * Date: 12/1/2020
 * File: EmployeeDetailsController.php
 * Description:
 */


namespace Bakery\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Bakery\Models\EmployeeDetails as Details;
class EmployeeDetailsController
{
    //list all employee details in database
    public function index(Request $request, Response $response, array $args){
        $results = Details::getDetails();
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    //get single employee detail by id
    public function view(Request $request, Response $response, array $args){
        $id = $args['id'];
        $results = Details::getDetailById($id);
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    // Create an employee detail
    public function create(Request $request, Response $response, array $args)
    {
        // Insert a new detail
        $detail = Details::createDetail($request);
        if ($detail->id) {
            $results = [
                'status' => 'Detail created',
                'detail_uri' => '/details/' . $detail->id,
                'data' => $detail
            ];
            $code = 201;
        } else {
            $code = 500;
        }

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    // Update a detail
    public function update(Request $request, Response $response, array $args)
    {
        // put in new detail
        $detail = Details::updateDetail($request);
        if ($detail->id) {
            $results = [
                'status' => 'Employee Detail updated',
                'details_uri' => '/details/' . $detail->id,
                'data' => $detail
            ];
            $code = 200;
        } else {
            $code = 500;
        }

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    // Delete a detail
    public function delete(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        Details::deleteDetail($id);
        $results = [
            'status' => "Detail '/details/$id' has been deleted.",
        ];
        $code = array_key_exists('status', $results) ? 200 : 500;
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

}