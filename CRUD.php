<?php

trait CRUD
{

    private $conn;

    /**
     * CRUD constructor.
     * @param $conn
     */
    public function __construct(\PDO $conn)
    {
        $this->conn = $conn;
    }


    public function save($tableName,$data){
        $arr = array();
        $values = "";
        foreach ($data as $item => $v) {
            $arr[$item] = ":".$item;
        }

        $values .= implode(",",$arr);


        $q = "INSERT INTO ".$tableName." VALUES (".$values.")";
        $stmt = $this->conn->prepare($q);
        $stmt->execute($data);

        $error = $stmt->errorInfo();

        if(empty($error[2])){
            return true;
        }else {
            return $error[2];
        }

    }

    public function display($tableName,$offset = 0,$limit = 0){
        $stmt = "";
        if(func_num_args() == 1){
            $q = "SELECT * FROM ".$tableName."  ORDER BY 2 ASC";
            $stmt = $this->conn->prepare($q);
            $stmt->execute();


        }else {
            $q = "SELECT * FROM ".$tableName."  ORDER BY 2 ASC LIMIT $offset,$limit";
            $stmt = $this->conn->prepare($q);
            $stmt->execute();
        }

        return $stmt;
    }


    public function displayNormal($tableName,$offset = 0,$limit = 0){
        $stmt = "";
        if(func_num_args() == 1){
            $q = "SELECT * FROM ".$tableName."  ORDER BY 1 ASC";
            $stmt = $this->conn->prepare($q);
            $stmt->execute();


        }else {
            $q = "SELECT * FROM ".$tableName."  ORDER BY 1 ASC LIMIT $offset,$limit";
            $stmt = $this->conn->prepare($q);
            $stmt->execute();
        }

        return $stmt;
    }



    public function findById($tableName,$condition,$id,$offset = 0,$limit = 0){
        $stmt = "";
        if(func_num_args() == 3) {
            $q = "SELECT * FROM ".$tableName." WHERE ".$condition." = ? ORDER BY 2 ";
            $stmt = $this->conn->prepare($q);
            $stmt->bindParam(1,$id,PDO::PARAM_INT);
            $stmt->execute();
        }else {
            $q = "SELECT * FROM ".$tableName." WHERE ".$condition." = ? ORDER BY 2 LIMIT $offset,$limit";
            $stmt = $this->conn->prepare($q);
            $stmt->bindParam(1,$id,PDO::PARAM_INT);
            $stmt->execute();
        }

        return $stmt;
    }


    public function deleteById($tableName,$id){
        $q = "DELETE FROM ".$tableName." WHERE id = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1,$id,PDO::PARAM_INT);
        $stmt->execute();

        $error = $stmt->errorInfo();

        if(empty($error[2])){
            return true;
        }else {
            return false;
        }
    }


    public function deleteByFiled($tableName,$filed,$id){
        $q = "DELETE FROM ".$tableName." WHERE ".$filed." = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1,$id,PDO::PARAM_INT);
        $stmt->execute();

        $error = $stmt->errorInfo();

        if(empty($error[2])){
            return true;
        }else {
            return false;
        }
    }


    function updateTable($table,&$fields,$condition) {
        $sql = "UPDATE $table set ";
        foreach($fields as $key => $value) {
            $fields[$key] = " $key = '".$value."' ";
        }
        $sql .= implode(" , ",array_values($fields))." WHERE id = ".$condition.";";

        $fields = array();
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $error = $stmt->errorInfo();

        if(empty($error[2])){
            return true;
        }else {
            return false;
        }

        

        
     }

     public function getOneFieldById($table,$field,$clause,$id){
        $q = "SELECT ".$field." FROM ".$table." WHERE ".$clause. " = ? limit 1";
        $stmt = $this->conn->prepare($q);
        $stmt->bindValue(1,$id);
        $stmt->execute();

        $row = $stmt->fetch();
        $f = $row[0];
        return $f;
     }


    


      public function getCountById($table,$user,$feild)
     {
        $q = "SELECT COUNT(*) FROM ". $table ." WHERE " . $feild . " = ?";
        $stmt = $this->conn->prepare($q);
        $stmt->bindValue(1,$user);
        $stmt->execute();

        $row = $stmt->fetch();
        $count = $row[0];
        return $count;
     }


     




}