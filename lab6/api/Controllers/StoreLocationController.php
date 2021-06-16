<?php
/**
 * Author: Jeanmarc Duong
 * Date: 12/1/2020
 * File: StoreLocationController.php
 * Description:
 */

namespace Bakery\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Bakery\Models\StoreLocation as Locations;
class StoreLocationController
{
    //list all locations in database
    public function index(Request $request, Response $response, array $args){
        $results = Locations::getLocation();
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    //get single location by id
    public function view(Request $request, Response $response, array $args){
        $id = $args['id'];
        $results = Locations::getLocationById($id);
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    // Create a location
    public function create(Request $request, Response $response, array $args)
    {
        // Insert a new location
        $location = Locations::createLocation($request);
        if ($location->id) {
            $results = [
                'status' => 'Location created',
                'location_uri' => '/locations/' . $location->id,
                'data' => $location
            ];
            $code = 201;
        } else {
            $code = 500;
        }

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    // Update a location
    public function update(Request $request, Response $response, array $args)
    {
        // Insert a new location
        $location = Locations::updateLocation($request);
        if ($location->id) {
            $results = [
                'status' => 'Location updated',
                'location_uri' => '/locations/' . $location->id,
                'data' => $location
            ];
            $code = 200;
        } else {
            $code = 500;
        }

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    // Delete a location
    public function delete(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        Locations::deleteLocation($id);
        $results = [
            'status' => "Location '/locations/$id' has been deleted.",
        ];
        $code = array_key_exists('status', $results) ? 200 : 500;
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }
}