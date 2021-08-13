<?php declare(strict_types = 1);
/**
 * Route class - Facade for routes parameters
 * @author: Sanket Raut
 */

 namespace SimplePi\Framework;

 class Routes extends App {

    protected $router;

    public function __construct() {
        $this->_setupHttp();
    }

    public static function build($callback) {
        $app = new self;
        $app->router = new \SimplePi\Kernel\Router(
            $app->request,
            $app->response,
            $app->dependencies
        );
        $callback($app->router);

        return $app;
    }

    // Optional - To include CORS access
    public function withCors() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        return $this;
    }

    // dispatch routes
    public function dispatch() {
        return $this->router->dispatchRoutes();
    }

    // setup dependencies
    private function _setupHttp() {
        $this->dependencies = new \SimplePi\Kernel\Dependencies;
        $this->request = $this->dependencies->injector->make('SimplePi\Http\HttpRequest');
        $this->response = $this->dependencies->injector->make('SimplePi\Http\HttpResponse');
    }

 }