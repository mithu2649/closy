<?php 
    include('classes/DB.php');
    
    if(isset($_POST['resetpassword'])){
        $cstrong = True;
        $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));

        $email=$_POST['email']; 
        $user_id = DB::query('SELECT id from users WHERE email=:email', array(':email'=>$email))[0]['id'];
        DB::query('INSERT INTO password_tokens VALUES(\'\',:token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
                
        echo 'email_sent';
        echo $token;
     }
?>

<h2>Forgot Password</h2>
<form action="forgot_password.php" method="post">
    <input type="text" name="email" id="email" placeholder="email">
    <input type="submit" name="resetpassword" value="reset password">

</form>