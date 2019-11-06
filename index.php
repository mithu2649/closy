<?php 
    include('classes/DB.php');
    include('classes/Login.php');


    if(Login::isLoggedIn()){
        echo 'logged_in with userID: ';
        echo Login::isLoggedIn();
    }else{
        echo 'not_logged_in';
    }
?>