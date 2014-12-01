<?php
/**
 * Created by Ivo Stefanov
 * Date: 12/1/14
 * Time: 9:16 PM
 */

namespace Zii;

use Whoops\Handler\PrettyPageHandler;

require __DIR__ . '/../vendor/autoload.php';

    error_reporting(E_ALL);

    $environment = 'development';

    /**
     * Register error handler
     */
    $woops = new \Whoops\Run;
    if($environment !== 'production') {
        $woops->pushHandler(new PrettyPageHandler());
    } else {
        $woops->pushHandler(function($e) {
            echo 'Friendly error page and send an email to the developer';
        });
    }
    $woops->register();


    /**
     * Create request and response components
     */
    $request = new \Http\HttpRequest($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    $response = new \Http\HttpResponse;

    $routeDefinitionCallback = function (\FastRoute\RouteCollector $r) {
        $routes = include('Routes.php');
        foreach ($routes as $route) {
            $r->addRoute($route[0], $route[1], $route[2]);
        }
    };
    $dispatcher = \FastRoute\simpleDispatcher($routeDefinitionCallback);
    $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPath());
    switch($routeInfo[0]) {
        case \FastRoute\Dispatcher::NOT_FOUND:
            $response->setContent('404 - Page not found');
            $response->setStatusCode(404);
            break;
        case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $response->setContent('405 - Method not allowed');
            $response->setStatusCode(405);
            break;
        case \FastRoute\Dispatcher::FOUND:
            $className = $routeInfo[1][0];
            $method = $routeInfo[1][1];
            $vars = $routeInfo[2];

            $class = new $className($response);
            $class->$method($vars);
            break;
    }
    /**
     * Send the response
     */
    foreach($response->getHeaders() as $header) {
        header($header);
    }

    echo $response->getContent();
