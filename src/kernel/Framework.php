<?php declare(strict_types = 1);
/**
 * Abstract class for framework structure
 * @author: Sanket Raut
 */

 namespace SimplePi\Kernel;

 abstract class Framework {
    protected static $app = [];
    protected $handler;
    protected $dependencies;
    protected $request;
    protected $response;
    protected $routes;

    /**
     * load configuration file for the app
     */
    public static function config() {}

    /**
     * load database info for the app
     */
    public static function db() {}
 }