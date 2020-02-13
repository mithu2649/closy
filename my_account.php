<?php 
    include('classes/DB.php');
    include('classes/Login.php');
    include('classes/Image.php');

    $showTimeline = False;
    if(Login::isLoggedIn()){
        $user_id = Login::isLoggedIn();
    }else{
        die('not_logged_in');
    }
   
    if(isset($_POST['uploadprofileimg'])){
       Image::uploadImage('profileimg', 'UPDATE users SET profile_img=:profile_img WHERE id=:user_id', array(':user_id'=>$user_id));   
    }

?>
<h1>My Account</h1>
<form action="my_account.php" method="post" enctype="multipart/form-data">
Upload an Image:
    <input type="file" name="profileimg"><br><br>
    <input type="submit" name="uploadprofileimg" value="upload image">
</form>