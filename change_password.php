<?php 
    include('classes/DB.php');
    include('classes/Login.php');
    $tokenIsValid = False;


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
        if(isset($_GET['token'])){
            $token = $_GET['token'];
            if(DB::query('SELECT user_id FROM password_tokens WHERE token=:token', array(':token'=>sha1($token)))){
                $user_id = DB::query('SELECT user_id FROM password_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
                $tokenIsValid = True;
                if(isset($_POST['changepassword'])){
                    $new_password =  $_POST['newpassword'];
                    $new_password_repeat =  $_POST['newpasswordrepeat'];
                    
                        if($new_password == $new_password_repeat){
                            if(strlen($new_password)>=6 && strlen($new_password)<=60){
                                DB::query('UPDATE users SET password=:newpassword WHERE id=:user_id', array(':newpassword'=>password_hash($new_password, PASSWORD_BCRYPT), ':user_id'=>$user_id));
                                echo 'password_changed_succesfully';

                                DB::query('DELETE FROM password_tokens WHERE user_id=:user_id', array(':user_id'=>$user_id));
                            }
                        }else{
                            echo 'passwords_dont_match';
                        }
                }
            }else{
                die('invalid_token');
            }
        }else{      
        die('not_logged_in');
    }
}
?>

<form action="<?php echo (!$tokenIsValid ? 'change_password.php' : 'change_password.php?token='.$token.'');  ?>" method="post">
    <h2>Change Password</h2>
    <?php
        if(!$tokenIsValid){
            echo '<input type="password" name="currentpassword" id="currentpassword" placeholder="current password"><br>';
        }
    ?>
    <input type="password" name="newpassword" id="newpassword" placeholder="new password"><br>
    <input type="password" name="newpasswordrepeat" id="newpasswordrepeat" placeholder="repeat new password"><br>
    <input type="submit" name="changepassword" value="change password">

</form>