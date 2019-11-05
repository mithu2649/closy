<?php
    include('classes/DB.php');
    if(isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        if(DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))){
          if(password_verify($password, DB::query('SELECT password FROM users WHERE username=:username', array(':username'=>$username))[0]['password'])){
              echo 'logged_in';
          }else{
              echo 'incorrect_password';
          }
        }else{
            echo 'user_not_found';
        }
    }
?>

<form action="login.php" method="post">
<input type="text" name="username" id="username" placeholder="Username">
    <input type="password" name="password" id="password" placeholder="Password">
    <input type="submit" name="login" value="Login">
</form>