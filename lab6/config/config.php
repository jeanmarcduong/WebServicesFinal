<?php
/**
 * Author: Jeanmarc Duong
 * Date: 12/1/2020
 * File: config.php
 * Description:
 */

return [
    /*
     * This option controls whether to display error details or not.
     * It should be set to true in the development environment.
     */
    'displayErrorDetails' => true,

    'addContentLengthHeader' => false,

    // Determine the application root folder location
    'app_root' => $_SERVER['DOCUMENT_ROOT']. '/i425/lab6',

    /*
     * This array contains database connection settings.
     */
    'db' => [
        'driver' => "mysql",
        'host' => 'localhost',
        'port' => 3306,
        'database' => 'bakery1',
        'username' => 'phpuser',
        'password' => 'phpuser',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '' //this is optional
    ]

];