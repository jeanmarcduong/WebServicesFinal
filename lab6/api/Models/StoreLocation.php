<?php
/**
 * Author: Jeanmarc Duong
 * Date: 10/24/2020
 * File: StoreLocation.php
 * Description:
 */

namespace Bakery\Models;


use Illuminate\Database\Eloquent\Model;

class StoreLocation extends Model
{
    protected $table = "storelocation";
    protected $primaryKey= 'id';
    public $timestamps = false;

    //get all locations
    public static function getLocation(){
        //all() method only retrieves the details
        $orders = self::all();
        return $orders;
    }

    //get location by id
    public static function getLocationById($id){
        $location = self::findOrFail($id);
        return $location;
    }

    //create a location
    public static function createLocation($request)
    {
        $params = $request->getParsedBody();

        //Creates a new employee detail object
        $location = new StoreLocation();

        foreach ($params as $field => $value) {
            $location->$field = $value;
        }

        $location->save();
        return $location;
    }


    //update a location
    public static function updateLocation($request)
    {
        $params = $request->getParsedBody();
        $id = $request->getAttribute('id');
        $location = self::findOrFail($id);

        foreach ($params as $field => $value) {
            $location->$field = $value;
        }
        $location->save();
        return $location;
    }


    // Delete a location
    public static function deleteLocation($id)
    {
        $order = self::findOrFail($id);
        return ($order->delete());
    }


}