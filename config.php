<?php

// declare constants

define('APP_PATH', 'myApp/');
define('MODELS', 'models/');
define('VIEWS', 'views/');
define('CONTROLLERS', 'controllers/');

// autoloader - for clases
spl_autoload_register(function($className){

    $relPath = '';

    $class = strtolower($className);  

    if(substr_count($class, 'controller')) $relPath = CONTROLLERS;
    if(substr_count($class, 'model')) $relPath = MODELS;
    if(substr_count($class, 'view')) $relPath = VIEWS; 

    // the path I will search for the file

    if($relPath == '') die ('invalid PATH!');

    $filePath = APP_PATH . $relPath . $className .'.php';

    // echo $filePath;

    if(file_exists($filePath)){
        require_once $filePath;
    }
    else {
        die("File NOT found! - $filePath");
    }
});

