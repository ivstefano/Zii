<?php
/**
 * Created by Ivo Stefanov
 * Date: 12/1/14
 * Time: 11:01 PM
 */
 
namespace Zii\Controllers;
use Http\Request;
use Http\Response;

class Homepage {
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }

    public function show() {
        $content = '<h1>Hello World</h1>';
        $content .= 'Hello ' . $this->request->getParameter('name', 'stranger');
        $this->response->setContent($content);
    }
}