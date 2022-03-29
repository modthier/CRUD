<?php
/**
 * Created by PhpStorm.
 * User: modth
 * Date: 12/8/2018
 * Time: 1:30 PM
 */

trait check
{
   public $errors;
   public $path;
   public $dir;

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @param mixed $dir
     */
    public function setDir($dir)
    {
        $this->dir = $dir;
    }


    public function getErrors()
    {
        return $this->errors;
    }

    public function setErrors($error)
    {
        
        $this->errors[] =  $error;
    }

    public function checkDefault($value,$type)
    {
        if ($value === "default") {
           $this->setErrors(" "."عفوا عليك اختيار "." ".$type);   
        }
    }

    public function checkEmail($email){
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $this->setErrors("$email is not a valid email address");
        }
    }


    public function checkNumber($number){
        $length = strlen($number);
        if ($length < 10){
            $this->setErrors("  رقم الهاتف لا يقل او يزيد من عشرة ارقام ");
        }

        
    }

    public function checkName($name)
    {
        if (!preg_match("/^[\p{L} ]+$/u",$name)) {
            $this->setErrors("name can not contain numbers");
        }
    }

    public function checkEmpty($inputs)
    {
        if (is_array($inputs)) {
          foreach ($inputs as $key => $value) {
            if (empty($value)) {
                $this->setErrors($key." is required");
            }
          }  
        }else {
            if(empty($inputs)){
              $this->setErrors("this field is required");  
            }
        }
        
    }



    public function validateImage($file){

        $root = $_SERVER['DOCUMENT_ROOT']."/".$this->dir;

        $target_file = $root.$file['name'];

        $type = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));

        $types = ['png','jpg','jepg','xlsx','pdf','doc','docx','ppt','pptx'];

        if (!in_array($type, $types)) {
            $this->setErrors('الصيغ المدعومة للغلاف هي '.'('.'jpg,jepg,png,xlsx,pdf,doc,docx,ppt,pptx'.')');
        }

        if(file_exists($target_file)){
            $this->setErrors('الصورة موجودة مسبقا');
        }

        return $target_file;

    }


    public function validateBook($file){

        $root = $_SERVER['DOCUMENT_ROOT']."/".$this->dir;

        $target_file = $root.$file['name'];

        $type = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));

        $types = ['pdf','doc','docx','ppt','pptx'];

        if (!in_array($type, $types)) {
            $this->setErrors('الصيغ المدعومة للكتاب هي '.'('.'xlsx,pdf,doc,docx,ppt,pptx'.')');
        }

        if(file_exists($target_file)){
            $this->setErrors('الكتاب موجودة مسبقا');
        }

        // return $root;

    }



    public function uploadImage($file){

        $root = $_SERVER['DOCUMENT_ROOT'].'/'.$this->dir;

        
        $target_file = $root;
        $ext= strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
        $fileName = uniqid(time()."_");

        if(!move_uploaded_file($file['tmp_name'],$target_file.$fileName.".".$ext)) {
            $this->setErrors( 'فشل في عملية ارسال الملف');
        }else {
            $this->setPath($fileName.".".$ext);
            return $fileName.'.'.$ext;
        }

    }


    public function validateMultipleImage($file){

        $root = $_SERVER['DOCUMENT_ROOT']."/".$this->dir;

        $target_file = $root.$file;

        $type = strtolower(pathinfo($file,PATHINFO_EXTENSION));

        $types = ['png','jpg','jepg'];

        if (!in_array($type, $types)) {
            $this->setErrors('الصيغ المدعومة هي '.'('.'jpg,jepg,png'.')');
        }

        if(file_exists($target_file)){
            $this->setErrors('الصورة موجودة مسبقا');
        }

    }

    public function uploadMultipleImage($file,$tmp_file){

        $root = $_SERVER['DOCUMENT_ROOT'].'/'.$this->dir;

        $this->setPath( $this->dir.$file);
        $target_file = $root;

        if(!move_uploaded_file($tmp_file,$target_file.$file)) {
            $this->setErrors( 'saving file is failed');
        }

    }


    public function makeFileName($file)
    {
        $pattern = ['+',' ','/','⁺','.','(',')'];
        $camelCase = ucwords($file);
        $name = str_replace($pattern, "", $camelCase);

        return  $name;
    }


    public function getAge($date)
    {
        
        $dob = new DateTime($date);
        
        $now = new DateTime();
         
        $difference = $now->diff($dob);
         
        $age = $difference->y." "." Year";

        if ($age <= 0) {
            $date1 = '2020-01-01';
            $date2 = '2020-07-11'; 
            $ts1 =strtotime($date1); 
            $ts2 = strtotime($date2); 
            $year1 = date('Y', $ts1); 
            $year2 = date('Y', $ts2); 
            $month1 = date('m', $ts1); $month2 = date('m', $ts2); 

            $diff = (($year2 - $year1) * 12) + ($month2 - $month1) ;
            $age = $diff." "." Month";
        }
         
        return  $age;
    }


    public function checkAmount($amount,$total_price){
        if ($amount < 0) {
            $this->setErrors( 'Please inter a proper amount');
        }

        if ($amount == 0) {
             $this->setErrors( 'Amount paid can not be Zero');
        }

        if ($amount > $total_price) {
            $this->setErrors( 'Amount paid can not be higher than total price');
        }

    }


    public function validateUser($current_user , $row_user)
    {
        if ($current_user == $row_user) {
            return true;
        }else {
            return false;
        }
    }

    public function matchPassword($password , $confirm_password)
    {
        if ($password !== $confirm_password ) {
            $this->setErrors('كلمة المرور وتاكيد كلمة المرور لا تتطابقان');
        }
    }

}
