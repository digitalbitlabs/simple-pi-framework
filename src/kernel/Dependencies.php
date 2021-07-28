<?php declare(strict_types = 1);
/**
 * Dependency injector for the framework
 * @author: Sanket Raut
 */
namespace SimplePi\Kernel;

use Auryn\Injector;

class Dependencies {
    /**
     * initialize the dependency class
     */
    public $injector;

    public function __construct() {
        $this->injector = new Injector;
        $this->__setupDependencies();
    }

    /**
     * setup dependencies and inject them
     */
    private function __setupDependencies() {
        $this->injector->alias('SimplePi\Http\Request','SimplePi\Http\HttpRequest');
        $this->injector->share('SimplePi\Http\HttpRequest');
        $this->injector->define('SimplePi\Http\HttpRequest', [
            ':get'      => $_GET,
            ':post'     => $_POST,
            ':cookies'  => $_COOKIE,
            ':files'    => $_FILES,
            ':server'   => $_SERVER
        ]);
        $this->injector->alias('SimplePi\Http\Response','SimplePi\Http\HttpResponse');
        $this->injector->share('SimplePi\Http\HttpResponse');

        return $this->injector;
    }
}