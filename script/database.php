<?php
class Database {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $connection;

    public function __construct($servername, $username, $password, $dbname) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->connection = $this->connectToDatabase();
    }
    public function connect() {
        $this->connection = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }
    private function connectToDatabase() {
        $connection = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }
        return $connection;
    }

    public function getConnection() {
        return $this->connection;
    }
    
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}     
?>