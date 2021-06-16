<?php
/**
 * Author: Jeanmarc Duong
 * Date: 12/1/2020
 * File: OrdersController.php
 * Description:
 */

namespace Bakery\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Bakery\Models\Orders;
class OrdersController
{
    //list all orders in database
    public function index(Request $request, Response $response, array $args){
        $results = Orders::getOrders();
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    //get single order by id
    public function view(Request $request, Response $response, array $args){
        $id = $args['id'];
        $results = Orders::getOrderById($id);
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    // Create an order
    public function create(Request $request, Response $response, array $args)
    {
        // Insert a new order
        $order = Orders::createOrder($request);
        if ($order->id) {
            $results = [
                'status' => 'Order created',
                'order_uri' => '/orders/' . $order->id,
                'data' => $order
            ];
            $code = 201;
        } else {
            $code = 500;
        }

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    // Update a order
    public function update(Request $request, Response $response, array $args)
    {
        // Insert a new order
        $order = Orders::updateOrder($request);
        if ($order->id) {
            $results = [
                'status' => 'Order updated',
                'orders_uri' => '/orders/' . $order->id,
                'data' => $order
            ];
            $code = 200;
        } else {
            $code = 500;
        }

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    // Delete an order
    public function delete(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        Orders::deleteOrder($id);
        $results = [
            'status' => "Order '/orders/$id' has been deleted.",
        ];
        $code = array_key_exists('status', $results) ? 200 : 500;
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

}