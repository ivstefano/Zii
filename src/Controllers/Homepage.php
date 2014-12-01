<?php
/**
 * Created by Ivo Stefanov
 * Date: 12/1/14
 * Time: 11:01 PM
 */
 
namespace Zii\Controllers;
use Http\Response;

class Homepage {
    /**
     * @var Response
     */
    private $response;

    public function __construct(Response $response) {
        $this->response = $response;
    }

    public function show() {
        $this->response->setContent('Hello World');
    }
}