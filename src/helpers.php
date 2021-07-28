<?php declare(strict_types = 1);
/** @author: Sanket Raut **

 * Environment variable helper
 */
if(!function_exists('env')) {
    function env($var,$default='') {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
        return isset($_ENV[$var])?$_ENV[$var]:$default;
    }
}
/**
 * Debug helper
 */
if(!function_exists('dd')) {
    function dd($content) {        
        header('Content-Type: application/json');
        print_r($content);
        exit();
    }
}
/**
 * Request component helper
 */
if(!function_exists('request')) {
    function request() {
        return new \SimplePi\Http\HttpRequest;
    }
}
/**
 * Response component helper
 */
if(!function_exists('response')) {
    function response() {
        return new \SimplePi\Http\HttpResponse;
    }
}
/**
 * Configuration variables helper
 */
if(!function_exists('config')) {
    function config($var) { 
        return \SimplePi\Framework\Config::get($var);
    }
}
/**
 * App folder path helper
 */
if(!function_exists('app_path')) {
    function app_path($var = '') { 
        return filter_input(INPUT_SERVER,'DOCUMENT_ROOT').'/../app/'.$var;
    }
}
/**
 * Abort function helper
 */
if(!function_exists('abort')) {
    function abort($message = 'Something went wrong') { 
        throw new RuntimeException($message);
    }
}

