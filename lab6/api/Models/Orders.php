<?php
/**
 * Author: Jeanmarc Duong
 * Date: 10/24/2020
 * File: Orders.php
 * Description:
 */

namespace Bakery\Models;


use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = "orders";
    protected $primaryKey= 'id';
    public $timestamps = false;

    public function menu(){
        return $this->belongsTo(Menu::class, 'id');
    }

    //get all orders
    public static function getOrders(){
        //all() method only retrieves the details
        $orders = self::all();
        return $orders;
    }

    //get orders from an id
    public static function getOrderById($id){
        $orders = self::findOrFail($id);
        return $orders;
    }

    //create an order
    public static function createOrder($request)
    {
        $params = $request->getParsedBody();

        //Cretea a new employee detail object
        $orders = new Orders();

        foreach ($params as $field => $value) {
            $orders->$field = $value;
        }

        $orders->save();
        return $orders;
    }


    //update an order
    public static function updateOrder($request)
    {
        $params = $request->getParsedBody();
        $id = $request->getAttribute('id');
        $orders = self::findOrFail($id);

        foreach ($params as $field => $value) {
            $orders->$field = $value;
        }
        $orders->save();
        return $orders;
    }


    // Delete an order
    public static function deleteOrder($id)
    {
        $order = self::findOrFail($id);
        return ($order->delete());
    }

}