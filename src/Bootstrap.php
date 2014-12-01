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
