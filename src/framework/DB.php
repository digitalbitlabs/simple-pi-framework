<?php declare(strict_types = 1);
/**
 * Database wrapper class for PHP PDO - Facade for DB class
 * @author: Sanket Raut
 */

 namespace SimplePi\Framework;

 class DB extends App {

    protected $connection;
    protected $results = [];

    public function __construct() {
        $this->connection = App::db();
    }

    public static function query($query) {
        // write orm query builder function
        $db = new self;
        try {
            foreach($db->connection->query($query) as $row) {
                $db->results[] = $row;
            }
        } catch (PDOException $e) {
            abort("DB Error: " . $e->getMessage());
        }
        return $db;
    }

    public function result() {
        try {
            $this->connection = null;
            return $this->results;    
        } catch(Exception $e) {
            abort("DB Error: " . $e->getMessage());
        } 
    }
 }
