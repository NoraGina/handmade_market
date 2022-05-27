<?php

class DBModel
{
    protected $conn;

    public function db(){
        $this->conn = new mysqli('localhost', 'myuser', '123', 'market_handmade');
        if($this->conn->connect_error){
            die('Connection error!');
        }
        return $this->conn;
    } 
}