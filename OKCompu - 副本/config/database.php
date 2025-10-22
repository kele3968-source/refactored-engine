<?php
/**
 * 数据库配置文件
 */

define('DB_HOST', 'localhost');
define('DB_PORT', '3306');  // MySQL默认端口
define('DB_USER', 'iris1_dvdv_top');
define('DB_PASS', 'iris1_dvdv_top');
define('DB_NAME', 'iris1_dvdv_top');
define('DB_CHARSET', 'utf8mb4');

/**
 * 数据库连接类
 */
class Database {
    private $connection;
    private static $instance = null;
    
    private function __construct() {
        $this->connect();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function connect() {
        try {
            // 包含端口号的DSN连接字符串
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $this->connection = new PDO($dsn, DB_USER, DB_PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("数据库连接失败: " . $e->getMessage());
        }
    }
    
    public function getConnection() {
        return $this->connection;
    }
}
?>