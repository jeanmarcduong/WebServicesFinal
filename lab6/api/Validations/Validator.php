<?php
/**
 * Author: Jeanmarc Duong
 * Date: 11/18/2020
 * File: Validator.php
 * Description:
 */

namespace Bakery\Validations;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
    private static $errors = [];
// A generic validation method. It returns true on success; an array of
//errors on false.
    public static function validate($request, array $rules)
    {
        foreach ($rules as $field => $rule) {
// Retrieve a parameter from url or the request body; id can besent in url or body.
            $param = $request->getAttribute($field) ?? $request->getParam($field);
try {
    $rule->setName(ucfirst($field))->assert($param);
} catch (NestedValidationException $ex) {
    self::$errors[$field] = $ex->getMessages();
}
}
        return empty(self::$errors);
    }
// Validate attributes of a employee model. Do not include fields having default values (id, role, etc.)
    public function validateEmployee($request)
    {
        $rules = [
            'firstName' => v::notEmpty(),
            'username' => v::notEmpty(),
            'role' => v::notEmpty(),
            'password' => v::notEmpty(),

        ];
        return self::validate($request, $rules);
    }

    // Validate attributes of a employee model. Do not include fields having default values (id, role, etc.)
    public function updateEmployee($request)
    {
        $rules = [
            'firstName' => v::noWhitespace()->notEmpty()->alnum(),
            'lastName' => v::notEmpty(),
            'role' => v::notEmpty(),
            'password' => v::notEmpty(),

        ];
        return self::validate($request, $rules);
    }

    // Return the errors in an array
    public static function getErrors()
    {
        return self::$errors;
    }
}

