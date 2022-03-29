<?php

use Carbon\Carbon;

class root
{
    use check,connect,CRUD;

     public function __construct(\PDO $conn)
    {
        $this->conn = $conn;
    }


}
