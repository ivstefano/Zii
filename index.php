<?php
/**
 * Created by Ivo Stefanov
 * Date: 10/29/14
 * Time: 3:37 AM
*/
require_once 'lib/DI.php';

$di = new DI();

// Using an anonymous function
$di->set('request', function(){
    return new Request();
});

// Using a class name non-shared
$di->set('sharedRequest', 'Request', true);

// Using a created object
$di->set('requestObject', new Request());

$request = $di->get('request');
$request->answer = "Not the answer";
var_dump($di->get('request'));

$request = $di->getShared('sharedRequest');
$request->answer = "Not the answer";
var_dump($di->getShared('sharedRequest'));

$request = $di->get('requestObject');
$request->answer = "Not the answer";
var_dump($di->get('requestObject'));

$di->remove('request');
$request = $di->get('request');
