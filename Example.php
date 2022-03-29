
<?php session_start();
    // this is an example of using curd trait    
    include 'conn.php'; // include your connection to database
    
    $root = new root($conn);

    $root->checkEmpty($_POST);
    

    $error = $root->getErrors();

    if(empty($error)){
        $success = $inc->saveWithoutId('tableName_here',$_POST);
        header("Location:index.php?view=inc");
        exit();
    }else {
        $_SESSION['errArr'] = $error;
        header("Location:index.php?view=inc");
        exit();
    }
