<?php
/**
 * Author: Jeanmarc Duong
 * Date: 10/22/2020
 * File: Employees.php
 * Description:
 */

namespace Bakery\Models;


use \Illuminate\Database\Eloquent\Model;
use Firebase\JWT\JWT;


class Employee extends Model
{

    protected $table = 'employees';
    protected $primaryKey = 'id';
    public $timestamps = false;
    const JWT_KEY = 'my-token';//it can be any token that users like
    const JWT_EXPIRE = 600;//

    public function employeedetails()
    {
        return $this->hasMany(EmployeeDetails::class, 'employeeId');
    }


    public static function getEmployees($request)
    {

        //get the total number of employees
        $count = self::count();

        //get query string variables from url
        $params = $request->getQueryParams();

        //Do limit and offset exist?
        $limit = array_key_exists('limit', $params) ? (int)$params['limit'] : 10; // items per page
        $offset = array_key_exists('offset', $params) ? (int)$params['offset'] : 0; // offset of the first item

        //Get search terms
        $term = array_key_exists('q', $params) ? $params['q'] : null;

        if (!is_null($term)) {
            $messages = self::searchEmployees($term);
            return $messages;
        } else {
            //Pagination
            $links = self::getLinks($request, $limit, $offset);

            // Sorting.
            $sort_key_array = self::getSortKeys($request);

            $query = Employee::with('employeedetails');
            //$query = Employee::all();
            $query = $query->skip($offset)->take($limit);  // limit the rows

            // sort the output by one or more columns
            foreach ($sort_key_array as $column => $direction) {
                $query->orderBy($column, $direction);
            }

            $employees = $query->get();

            //construct data for the response
            $results = [
                'totalCount' => $count,
                'limit' => $limit,
                'offset' => $offset,
                'links' => $links,
                'sort' => $sort_key_array,
                'data' => $employees
            ];
            return $results;
        }
    }

    //get a employee by employee id
    public static function getEmployeeById($id)
    {
        $message = self::findOrFail($id);
        return $message;
    }
    //get the details of an employee based off of a the id
    public static function getDetailsByEmployee($id)
    {
        $details = self::findOrFail($id)->employeedetails;
        return $details;
    }


    // Create a new employee
    public static function createEmployee($request)
    {
        // Retrieve parameters from request body
        $params = $request->getParsedBody();

        // Create a new employee instance
        $employee = new Employee();

        // Set the employee's attributes
        foreach ($params as $field => $value) {

            //hashing the password
            if ($field == 'password') {
                $value = password_hash($value, PASSWORD_DEFAULT);
            }

            $employee->$field = $value;
        }

        // Insert the employee into the database
        $employee->save();
        return $employee;
    }


    // Update a employee
    public static function updateEmployee($request)
    {
        // Retrieve parameters from request body
        $params = $request->getParsedBody();

        //Retrieve the employee's id from url and then the employee from the database
        $id = $request->getAttribute('id');
        $employee = self::findOrFail($id);

        // Update attributes of the employee
        $employee->firstName = $params['firstName'];
        $employee->lastName = $params['lastName'];
        $employee->password = password_hash($params['password'], PASSWORD_DEFAULT);
        $employee->role = $params['role'];

        // Update the professor
        $employee->save();
        return $employee;
    }

    // Delete an employee
    public static function deleteEmployee($id)
    {
        $employee = self::findOrFail($id);
        return ($employee->delete());
    }


    // Authenticate a user by username and password. Return the user.
    public static function authenticateEmployee($username, $password)
    {
        $user = self::where('username', $username)->first();
        if (!$user) {
            return false;
        }
        return password_verify($password, $user->password) ? $user : false;
    }


    public static function getLinks($request, $limit, $offset)
    {
        $count = self::count();
// Get request uri and parts
        $uri = $request->getUri();
        $base_url = $uri->getBaseUrl();
        $path = $uri->getPath();
// Construct links for pagination
        $links = array();
        $links[] = ['rel' => 'self', 'href' => $base_url . $path . "?
limit=$limit&offset=$offset"];
        $links[] = ['rel' => 'first', 'href' => $base_url . $path . "?
limit=$limit&offset=0"];
        if ($offset - $limit >= 0) {
            $links[] = ['rel' => 'prev', 'href' => $base_url . $path . "?
limit=$limit&offset=" . ($offset - $limit)];
        }
        if ($offset + $limit < $count) {
            $links[] = ['rel' => 'next', 'href' => $base_url . $path . "?
limit=$limit&offset=" . ($offset + $limit)];
        }
        $links[] = ['rel' => 'last', 'href' => $base_url . $path . "?
limit=$limit&offset=" . $limit * (ceil($count / $limit) - 1)];
        return $links;
    }

    public static function getSortKeys($request)
    {
        $sort_key_array = array();

// Get querystring variables from url
        $params = $request->getQueryParams();

        if (array_key_exists('sort', $params)) {
            $sort = preg_replace('/^\[|\]$|\s+/', '', $params['sort']);
            $sort_keys = explode(',', $sort); //get all the key:direction pairs
            foreach ($sort_keys as $sort_key) {
                $direction = 'asc';
                $column = $sort_key;
                if (strpos($sort_key, ':')) {

                    list($column, $direction) = explode(':', $sort_key);
                }

                $sort_key_array[$column] = $direction;
            }

        }
        return $sort_key_array;
    }

    public static function searchEmployees($terms)
    {
        if (is_numeric($terms)) {
            $query = self::where('id', "like", "%$terms%");
        } else {
            $query = self::where('firstName', 'like', "%$terms%")->orWhere('lastName', 'like', "%$terms%");
        }
        $results = $query->get();
        return $results;
    }



    // Authenticate a user by owner. Return the role.
    public static function authenticateOwner($owner, $password)
    {
        //echo $owner, $password;
        $user = self::where('role', $owner)->first(); // why is it grey when I add employee?
        if ($owner == 'employee') {
            return false;
        }
        return password_verify($password, $user->password) ? $user : false;
    }
    /*
 * Generate a JWT token.
 * The signature secret rule: the secret must be at least 12 characters
 in length;
 * contain numbers; upper and lowercase letters; and one of the
 following special characters *&!@%^#$.
 * For more details, please visit
 https://github.com/RobDWaller/ReallySimpleJWT
 */
    public static function generateJWT($id)
    {
// Data for payload
        $user = $user = self::findOrFail($id);
        if (!$user) {
            return false;
        }
        $key = self::JWT_KEY;
        $expiration = time() + self::JWT_EXPIRE;
        $issuer = 'mychatter-api.com';
        $token = [
            'iss' => $issuer,
            'exp' => $expiration,
            'isa' => time(),
            'data' => [
                'uid' => $id,
                'username' => $user->username,
                'firstName' => $user->firstName,
            ]
        ];
//        4. In UserController.php, we need to add new method that will return JWT token if the user
//provides correct username and password.
//    Find the end of this file and add a new method right before the closing }.
//5. Then, let's create the middleware of JWTAuthenticator class.
//// Generate and return a token
// Generate and return a token
        return JWT::encode(
            $token, // data to be encoded in the JWT
            $key, // the signing key
            'HS256' // algorithm used to sign the token; defaults to HS256
        );
    }
// Verify a token
    public static function validateJWT($token)
    {
        $decoded = JWT::decode($token, self::JWT_KEY, array('HS256'));
        return $decoded;
    }















}
