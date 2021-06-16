<?php
/**
 * Author: Jeanmarc Duong
 * Date: 12/1/2020
 * File: MenuController.php
 * Description:
 */


namespace Bakery\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Bakery\Models\Menu as Menu;
class MenuController
{
    //list all menu items in database
    public function index(Request $request, Response $response, array $args){
        $results = Menu::getMenu($request);
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    //get single menu item by id
    public function view(Request $request, Response $response, array $args){
        $id = $args['id'];
        $results = Menu::getMenuById($id);
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    //view the orders of a menu item
    public function viewMenuOrder(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        $results = Menu::getMenuOrder($id);
        $code = array_key_exists("status", $results) ? 500 : 200;

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }


    // Create a menu item
    public function create(Request $request, Response $response, array $args)
    {
        // Insert a new student
        $menu = Menu::createMenu($request);
        if ($menu->id) {
            $results = [
                'status' => 'Menu item created',
                'menu_uri' => '/menu/' . $menu->id,
                'data' => $menu
            ];
            $code = 201;
        } else {
            $code = 500;
        }

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }
    // Update a menu item
    public function update(Request $request, Response $response, array $args)
    {
        // Insert a new student
        $menu = Menu::updateMenu($request);
        if ($menu->id) {
            $results = [
                'status' => 'Menu item updated',
                'menu_uri' => '/menu/' . $menu->id,
                'data' => $menu
            ];
            $code = 200;
        } else {
            $code = 500;
        }

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }


    // Delete a menu item
    public function delete(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        Menu::deleteMenu($id);
        $results = [
            'status' => "Menu '/menu/$id' has been deleted.",
        ];
        $code = array_key_exists('status', $results) ? 200 : 500;
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

}