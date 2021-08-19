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
            $db->connection = $db->connection == null?self::$app:$db->connection;
            foreach($db->connection->query($query, \PDO::FETCH_ASSOC) as $row) {
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

    public function first() {
        try {
            $this->connection = null;
            return isset($this->results[0])?$this->results:[];    
        } catch(Exception $e) {
            abort("DB Error: " . $e->getMessage());
        } 
    }
 }
