<?php declare(strict_types = 1);
/**
 * App wrapper class for booting the configuration
 * @author: Sanket Raut
 */

 namespace SimplePi\Framework;

 use SimplePi\Exceptions\Handler;
 
 class App extends \SimplePi\Kernel\Framework {

    public function __construct() {
        $this->handler = new Handler;
        $this->handler->handleException();
    }

    /**
     * bootstrap the application
     */
    public function boot() {
        $this->loadRoutes();
        $this->loadConfig();
        $this->loadDB();
    }

    /**
     * load configuration file for the app
     */
    public static function config() {
        return self::$app = self::$app == null?require_once(app_path('/config.php')):self::$app;
    }

    /**
     * load database setings for the app
     */
    public static function db() {
        $config = self::config();
        if(is_array($config)) {
            $pdo = $config['database'];
            if(isset($pdo['charset'])) {     
                return self::$app = new \PDO($pdo['driver'].':host='.$pdo['host'].';dbname='.$pdo['database'].';charset='.$pdo['charset'], $pdo['username'], $pdo['password'],array(\PDO::ATTR_PERSISTENT => true));    
            }
            return self::$app = new \PDO($pdo['driver'].':host='.$pdo['host'].';dbname='.$pdo['database'], $pdo['username'], $pdo['password'],array(\PDO::ATTR_PERSISTENT => true));    
        }
    }

    /**
     * load config private method to use in boot()
     */
    private function loadConfig() {
        return self::config();
    }

    /**
     * load config private method to use in boot()
     */
    private function loadDB() {
        return self::db();
    }

    /**
     * load routes private method to use in boot()
     */
    private function loadRoutes() {
        return require_once(app_path('/routes.php'));
    }

 }