<?php declare(strict_types = 1);
/**
 * Database wrapper class for PHP PDO - Facade for DB class
 * @author: Sanket Raut
 */

 namespace SimplePi\Framework;

 class DB extends App {

    protected $connection;
    protected $collection;
    protected $table;
    protected $query;
    protected $field;
    protected $fieldIn;
    protected $fieldNotIn;
    protected $value;
    protected $valueSet;
    protected $valueIn;
    protected $valueNotIn;
    protected $results = [];

    public function __construct() {
        $this->connection = App::db();
    }

    public static function query($query) {
        // write orm query builder function
        $db = new self;
        try {
            $db->connection = $db->connection == null?self::$app:$db->connection;
            $db->collection = $db->connection->query($query, \PDO::FETCH_ASSOC);
            if($db->collection) {
                foreach($db->collection as $row) {
                    $db->results[] = $row;
                }
            } else {
                throw new \PDOException('DB Error: '.$query);
            }
        } catch (PDOException $e) {
            abort("DB Error: " . $e->getMessage());
        }
        return $db;
    }

    public static function table($table) {
        $db = new self;
        try {
            $db->connection = $db->connection == null?self::$app:$db->connection;
            $db->table = $table;
        } catch (PDOException $e) {
            abort("DB Error: " . $e->getMessage());
        }
        return $db;
    }

    public function where($field,$value) {
        try {
            $this->field = $field;
            $this->value = $value;
            return $this;
        } catch(PDOException $e) {
            abort("DB Error: " . $e->getMessage());
        }
    }

    public function update($data) {
        try {
            $data = (array) $data;
            $keyPairs = array_map(function($val) {
                return $val = $val.'=:'.$val;
            },array_keys($data));
            $data[$this->field] = $this->value;
            $this->query = "UPDATE ".$this->table;
            if(isset($this->field) && isset($this->value)) {
                $this->query .= " SET ".implode(',',$keyPairs)." WHERE ".$this->field."=:".$this->field;
            }
            if(isset($this->fieldIn) && isset($this->valueIn)) {
                $this->query .= " SET ".implode(',',$keyPairs)." WHERE ".$this->fieldIn." IN (".str_repeat("?,", count($this->valueIn)-1) . "?".")";
            }
            $this->connection->prepare($this->query)->execute($data);
        } catch (PDOException $e) {
            abort("DB Error: " . $e->getMessage());
        }
        return $this;
    }

    public function insert($data) {
        try {
            $data = (array) $data;
            $keyPairs = $data;
            array_walk($keyPairs,function(&$val,$key) {
                $val = ':'.$key;
            });
            $keys = array_map(function($val) {
                return '`'.$val.'`';
            },array_keys($keyPairs));
            $values = array_values($keyPairs);
            $this->query = "INSERT INTO ".$this->table." (".implode(',',$keys).") VALUES(".implode(',',$values).") ";
            $this->connection->prepare($this->query)->execute($data);
        } catch (PDOException $e) {
            abort("DB Error: " . $e->getMessage());
        }
        return $this;
    }

    public function insertGetId($data) {
        try {
            $data = (array) $data;
            $keyPairs = $data;
            array_walk($keyPairs,function(&$val,$key) {
                $val = ':'.$key;
            });
            $keys = array_map(function($val) {
                return '`'.$val.'`';
            },array_keys($keyPairs));
            $values = array_values($keyPairs);
            $this->query = "INSERT INTO ".$this->table." (".implode(',',$keys).") VALUES(".implode(',',$values).") ";
            $this->connection->prepare($this->query)->execute($data);
            return $this->connection->lastInsertId();
        } catch (PDOException $e) {
            abort("DB Error: " . $e->getMessage());
        }
        return $this;
    }

    public function delete() {
        try {
            $this->query = "DELETE FROM ".$this->table." WHERE 1 = 1";
            $this->valueSet = [];
            if(isset($this->field) && isset($this->value)) {
                $this->query .= " AND ".$this->field."= ?";
                $this->valueSet = array_merge($this->valueSet,[$this->value]);
            }
            if(isset($this->fieldIn) && isset($this->valueIn)) {
                $this->query .= " AND ".$this->fieldIn." IN (".str_repeat("?,", count($this->valueIn)-1) . "?".")";
                $this->valueSet = array_merge($this->valueSet,$this->valueIn);
            }
            if(isset($this->fieldNotIn) && isset($this->valueNotIn)) {
                $this->query .= " AND ".$this->fieldNotIn." NOT IN (".str_repeat("?,", count($this->valueNotIn)-1) . "?".")";
                $this->valueSet = array_merge($this->valueSet,$this->valueNotIn);
            }
            $this->connection->prepare($this->query)->execute($this->valueSet);
        } catch (PDOException $e) {
            abort("DB Error: " . $e->getMessage());
        }
        return $this;
    }

    public function whereIn($field,$collection) {
        $this->fieldIn = $field;
        $this->valueIn = $collection;
        return $this;
    }

    public function whereNotIn($field,$collection) {
        $this->fieldNotIn = $field;
        $this->valueNotIn = $collection;
        return $this;
    }

    public function first() {
        try {
            $this->connection = null;
            return isset($this->results[0])?$this->results[0]:[];    
        } catch(Exception $e) {
            abort("DB Error: " . $e->getMessage());
        } 
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
