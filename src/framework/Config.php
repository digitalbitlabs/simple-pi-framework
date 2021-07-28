<?php declare(strict_types = 1);
/**
 * Config class - Facade for configuration parameters
 * @author: Sanket Raut
 */

 namespace SimplePi\Framework;

 class Config extends App {

    private static $_parameters;

    public static function get($var) {
      self::$_parameters = App::config();
      $args = explode('.',$var);
      return count($args) > 1?self::$_parameters[$args[0]][$args[1]]:$var;
    }

 }