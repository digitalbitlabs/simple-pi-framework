<?php declare(strict_types = 1);
/** @author: Sanket Raut **/
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
        $vendorPath = 'vendor'.DIRECTORY_SEPARATOR.'digitalbitlabs'.DIRECTORY_SEPARATOR.'simple-pi-framework'.DIRECTORY_SEPARATOR.'src';
        return str_replace($vendorPath,'',dirname(__FILE__)).'/app/'.$var;
    }
}
/**
 * App folder path helper
 */
if(!function_exists('base_path')) {
    function base_path($var = '') {
        $vendorPath = 'vendor'.DIRECTORY_SEPARATOR.'digitalbitlabs'.DIRECTORY_SEPARATOR.'simple-pi-framework'.DIRECTORY_SEPARATOR.'src';
        return str_replace($vendorPath,'',dirname(__FILE__)).$var;
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
/**
 * Load env vars by calling env function function from Illuminate\Support\helpers.php 
 */
if(!function_exists('load_env_vars')) {
    function load_env_vars() {
        $dotenv = Dotenv\Dotenv::createImmutable(base_path());
        $dotenv->load();
        foreach($_ENV as $var => $val) {
            env($var);
        }
    }
}
/*
* Environment variable helper
*/
if(!function_exists('env')) {
   function env($var,$default = null) {
       try {
           $dotenv = Dotenv\Dotenv::createImmutable(base_path());
           $dotenv->load();
           return isset($_ENV[$var])?$_ENV[$var]:$default;    
       } catch(Exception $e) {
           throw new Exception($e->getMessage());
       }
   }
} else {
   // call default env function by loading env variables
   load_env_vars();
}