<?php

class ConnectionFactory
{
    private static $factory;
    private $db;

    public static function getFactory()
    {
        if (!self::$factory)
            self::$factory = new ConnectionFactory();
        return self::$factory;
    }

    public function getConnection()
    {
        if (!$this->db)
            $this->db = new mysqli(getenv('DBHOST'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'), getenv('DB_NAME'));
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
        return $this->db;
    }
}
