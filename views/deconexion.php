<?php 

    session_start();


    $user_type = isset($_GET['user']) ? htmlspecialchars($_GET['user']) : null;

    if (empty($user_type)) {
        header("Location: ../index.php"); 
        exit;
    }   

    switch ($user_type) {
        case 'eleve':
            header("Location:../index.php");
            exit;
            break;
        
        case 'prof':
            header("Location:login.php");
            exit;
            break;
        
        default :
            header("Location: ../index.php"); 
            exit;
            break;
    }

    

    

?>