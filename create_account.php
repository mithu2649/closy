<?php
    include('classes/DB.php');
    if(isset($_POST['create_account'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        if(!DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))){
            if(strlen($username)>=3 && strlen($username)<32){

                if(preg_match('/[a-zA-Z0-9_]+/', $username)){
                    if(strlen($password)>=6 && strlen($password)<=60){
                        if(filter_var($email, FILTER_VALIDATE_EMAIL)){

                            DB::query('INSERT INTO users VALUES(\'\',:username, :password, :email)', array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email));
                            echo "account_created";
                        }
                        else{
                            echo 'invalid_email';
                        }
                    }else{
                        echo 'invalid_password_length';
                    }
                }else{
                    echo 'invalid_chars_username';
                }
            }else{
                echo 'invalid_username';
            }
        }else{
            echo 'user_already_exists';
        }
    }

?>



<style>
    input{
        display:block;
    }
</style>
<form action="create_account.php" method="post">
    <input type="text" name="username" id="username" placeholder="Username">
    <input type="password" name="password" id="password" placeholder="Password">
    <input type="email" name="email" id="email" placeholder="Email ID">
    <input type="submit" name="create_account" value="Submit">
</form>