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


    public function saveWithoutId($tableName,$data){
        
        $values = "";
        $holder = [];
        $placeHolder = "";
        $trimedData = array();

        for ($i=0; $i < count($data); $i++) { 
          array_push($holder, "?");          
        }

        foreach ($data as $key => $value) {
            array_push($trimedData, trim($value));
        }

        $values .= implode(",",array_keys($data));

        $placeHolder .= implode(",",$holder);


        $q = "INSERT INTO ".$tableName." (".$values.") "." VALUES (".$placeHolder.")";
        $stmt = $this->conn->prepare($q);
        $stmt->execute(array_values($data));

        $error = $stmt->errorInfo();
        if(empty($error[2])){
            return $this->conn->lastInsertId();
        }else {
            return $error[2];
        }    
       
        

    }

    public function display($tableName,$offset = 0,$limit = 0){
        $stmt = "";
        if(func_num_args() == 1){
            $q = "SELECT * FROM ".$tableName."  ORDER BY 1 DESC";
            $stmt = $this->conn->prepare($q);
            $stmt->execute();


        }else {
            $q = "SELECT * FROM ".$tableName."  ORDER BY 1 DESC LIMIT $offset,$limit";
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


    public function displayWithLimit($tableName,$limit){
        
        $q = "SELECT * FROM ".$tableName."  ORDER BY 1 DESC limit $limit";
            $stmt = $this->conn->prepare($q);
            $stmt->execute();
        

        return $stmt;
    }


    public function countRows($db,$table)
    {
        $q = "SELECT  TABLE_ROWS 
              FROM information_schema.TABLES WHERE TABLES.TABLE_SCHEMA = ?
              AND TABLES.TABLE_NAME = ? ";
         $stmt = $this->conn->prepare($q);
         $stmt->bindValue(1,$db);
         $stmt->bindValue(2,$table);
         $stmt->execute();

         $row = $stmt->fetch();
         $count = $row['TABLE_ROWS'];
         return $count;
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
            return $error;
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


    


    function update($table,&$fields,$condition) {
        $sql = "UPDATE $table set ";
        foreach($fields as $key => $value) {
            $fields[$key] = " $key = '".addslashes($value)."' ";
        }
        $sql .= implode(" , ",array_values($fields))." WHERE id = ".$condition.";";

        $fields = array();
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $error = $stmt->errorInfo();

        if(empty($error[2])){
            return true;
        }else {
            return $error[2];
        }

        

     }

     function updateTableById($table,&$fields,$condition,$id) {
        $sql = "UPDATE $table set ";
        foreach($fields as $key => $value) {
            $fields[$key] = " $key = '".$value."' ";
        }
        $sql .= implode(" , ",array_values($fields))." WHERE ".$condition."  = ".$id.";";

        $fields = array();
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $error = $stmt->errorInfo();

        if(empty($error[2])){
            return true;
        }else {
            return $error[2];
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


    
     public function selectByMore($table,$fields)
     {
        $col = [];
        foreach ($fields as $key => $value) {
            array_push($col, $key." = ? ");
        }
        $values = implode(" and ", $col);

        $q = "SELECT * FROM ".$table." WHERE ".$values;
        $stmt = $this->conn->prepare($q);

        $i = 1;
        foreach ($fields as $field) {
          $stmt->bindValue($i,$field);
          $i++;
        }

        $stmt->execute();

        return $stmt;

     }


     public function getMedia($table,$id,$type)
     {
         $fields = ['image_type_id ' => $id , 'image_type' => $type];
         $stmt = $this->selectByMore($table,$fields);
         $row = $stmt->fetch();
         return ['image' => $row['image'] , 'id' => $row['id'] ];
     }



}
