<?php

namespace SimplePi\Framework;

use Illuminate\Database\Capsule\Manager as Capsule;

use SimplePi\Framework\Config;

/**
 * DB class used as a wrapper for Capsule Manager from Laravel's Illuminate
 */
class DB extends Capsule {

    public static function boot() {
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver'    => config('database.driver'),
            'host'      => config('database.host'),
            'database'  => config('database.database'),
            'username'  => config('database.username'),
            'password'  => config('database.password'),
            'charset'   => config('database.charset'),
            'collation' => config('database.collation'),
            'prefix'    => config('database.prefix')
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        static::$instance = $capsule;        
    }
}

/**
 * Initialize the class to let controllers access database methods globally
 */
DB::boot();