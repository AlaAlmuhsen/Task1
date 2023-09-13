<?php

class Database {
    public function __construct(private string $servername,private string $databasename,private string $user,private string $password)
    {
    }
    
    public function getConnection(){
        
            return $conn = new PDO("mysql:host={$this->servername};dbname={$this->databasename};", $this->user, $this->password, [
                PDO::ATTR_EMULATE_PREPARES => true,
                PDO::ATTR_STRINGIFY_FETCHES => true
            ]);
    }
   
}