<?php declare(strict_types = 1);
/**
 * Route class - Facade for routes parameters
 * @author: Sanket Raut
 */

 namespace SimplePi\Framework;

 class Routes extends App {

    public function __construct() {
        $this->_setupHttp();
    }

    public static function build($callback) {
        $app = new self;
        $router = new \SimplePi\Kernel\Router(
            $app->request,
            $app->response,
            $app->dependencies
        );
        $callback($router);
        return $router->dispatchRoutes();
    }

    private function _setupHttp() {
        $this->dependencies = new \SimplePi\Kernel\Dependencies;
        $this->request = $this->dependencies->injector->make('SimplePi\Http\HttpRequest');
        $this->response = $this->dependencies->injector->make('SimplePi\Http\HttpResponse');
    }

 }