<?php 
    include('classes/DB.php');
    include('classes/Login.php');


    if(Login::isLoggedIn()){

        if(isset($_POST['changepassword'])){
            $old_password =  $_POST['currentpassword'];
            $new_password =  $_POST['newpassword'];
            $new_password_repeat =  $_POST['newpasswordrepeat'];
            $user_id = Login::isLoggedIn();
            if(password_verify($old_password, DB::query('SELECT password FROM users WHERE id=:user_id', array(':user_id'=>$user_id))[0]['password'])){
                if($new_password == $new_password_repeat){
                    if(strlen($new_password)>=6 && strlen($new_password)<=60){
                        DB::query('UPDATE users SET password=:newpassword WHERE id=:user_id', array(':newpassword'=>password_hash($new_password, PASSWORD_BCRYPT), ':user_id'=>$user_id));
                        echo 'password_changed_succesfully';
                    }
                }else{
                    echo 'passwords_dont_match';
                }
            }else{
                echo 'incorrect_old_password';
            }
        }
    }else{
        die('not_logged_in');
    }
?>

<form action="change_password.php" method="post">
    <h2>Change Password</h2>
    <input type="password" name="currentpassword" id="currentpassword" placeholder="current password"><br>
    <input type="password" name="newpassword" id="newpassword" placeholder="new password"><br>
    <input type="password" name="newpasswordrepeat" id="newpasswordrepeat" placeholder="repeat new password"><br>
    <input type="submit" name="changepassword" value="change password">

</form>