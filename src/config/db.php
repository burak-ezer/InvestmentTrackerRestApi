<?php

class Db {
    private $dbhost = "localhost";
    private $dbuser = "root";
    private $dbpassword = "";
    private $dbname = "investment_tracker_db";

    public function connect(){
        $mysql_connection = "mysql:host=$this->dbhost;dbname=$this->dbname";
        $connection = new PDO($mysql_connection,$this->dbuser,$this->dbpassword);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    }
}