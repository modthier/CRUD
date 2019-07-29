
<?php session_start();
    // this is an example of using curd trait    
    include '../../../inc/conn.php';
    include '../../../classes/init.php';

    $inc->checkEmpty($_POST);
    $inc->checkName($_POST['insurance_type']);

    $error = $inc->getErrors();

    if(empty($error)){
        $success = $inc->save('insurance_types',$_POST);
        if($success) {
            $_SESSION['done'] = "Data Saved";
            header("Location:../../index.php?view=inc");
            exit();
        }else {
            $_SESSION['error'] = "Error Happend try a gain";
            header("Location:../../index.php?view=inc");
            exit();
        }
    }else {
        $_SESSION['errArr'] = $error;
        header("Location:../../index.php?view=inc");
        exit();
    }
