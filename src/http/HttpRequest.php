<?php declare(strict_types = 1);
/**
 * Http Request class to handle requests
 * @author: Sanket Raut
 */

namespace SimplePi\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

class HttpRequest {

    protected $request;
    protected $inputs;
    protected $collection;
    
    /**
     * Constructor function to initialize the config
     */
    public function __construct() {
        $this->request = Request::createFromGlobals();
        $this->inputs = $this->request->request;
        $this->collection = new ParameterBag;
    }

    /**
     * helper function method()
     */
    public function method() {
        return $this->request->getMethod();
    }

    /**
     * helper function path()
     */
    public function path() {
        return $this->request->getPathInfo();
    }

    /**
     * retrive all parameters from incoming request
     */
    public function all() {
        if($this->method() == 'POST') {
            if(!empty(filter_input(INPUT_SERVER,'HTTP_AJAX_USER_AGENT')) && strtolower(filter_input(INPUT_SERVER,'HTTP_AJAX_USER_AGENT')) == 'simplepi/framework') {
                return json_decode(file_get_contents('php://input', true));
            }
            return $this->inputs->all();
        }
        return $this->collection->all();
    }

    /**
     * add parameters to request
     */
    public function add($params) {
        return $this->collection->add($params);
    }

}