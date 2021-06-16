<?php
/**
 * Author: Jeanmarc Duong
 * Date: 10/22/2020
 * File: EmployeeDetails.php
 * Description:
 */

namespace Bakery\Models;


use \Illuminate\Database\Eloquent\Model;
class EmployeeDetails extends Model
{
    protected $table = "employeedetails";
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function employee(){
        return $this->belongsTo(Employee::class, 'id');
    }

    public static function getDetails(){
        //all() method only retrieves the details
        $details = self::all();
        return $details;
    }

    //get detail by id
    public static function getDetailById($id){
        $detail = self::findOrFail($id);
        return $detail;
    }

    //create a employee detail
    public static function createDetail($request)
    {
        $params = $request->getParsedBody();

        //Create a new employee detail object
        $detail = new EmployeeDetails();

        foreach ($params as $field => $value) {
            $detail->$field = $value;
        }

        $detail->save();
        return $detail;
    }


    //update a detail
    public static function updateDetail($request)
    {
        $params = $request->getParsedBody();
        $id = $request->getAttribute('id');
        $detail = self::findOrFail($id);

        foreach ($params as $field => $value) {
            $detail->$field = $value;
        }
        $detail->save();
        return $detail;
    }


    //delete an employee detail
    public static function deleteDetail($request)
    {
        $id = $request->getAttribute('id');
        $detail = self::findOrFail($id);
        return($detail->delete());
    }

}