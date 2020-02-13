<?php 
    include('classes/DB.php');
    include('classes/Login.php');

    $showTimeline = False;
    if(Login::isLoggedIn()){
        $user_id = Login::isLoggedIn();
    }else{
        die('not_logged_in');
    }
   
    if(isset($_POST['uploadprofileimg'])){
        
        $image = base64_encode(file_get_contents($_FILES['profileimg']['tmp_name']));

        $options = array('http'=>array(
            'method'=>"POST",
            'header'=>"Authorization: Bearer a8b246696dd2b4b1fd26343b4b42ec5e321bbff0\n".
            "Content-Type: application/x-www-form-urlencoded",
            'content'=>$image
        ));

        $context= stream_context_create($options);
        $imgurURL = "https://api.imgur.com/3/image";


        if($_FILES['profileimg']['size'] > 10240000){
            die('image_too_large: must be less than 10MB');
        }

        $response = file_get_contents($imgurURL, false, $context);
        $response = json_decode($response);

        //For debugging response from imgur server
        // echo '<pre>';
        // print_r($response);
        // echo '</pre>';

        $profile_img_link =  $response->data->link; 

        DB::query('UPDATE users SET profile_img=:profile_img WHERE id=:user_id', array(':profile_img'=>$profile_img_link, ':user_id'=>$user_id));

        
        
    }
?>
<h1>My Account</h1>
<form action="my_account.php" method="post" enctype="multipart/form-data">
Upload an Image:
    <input type="file" name="profileimg"><br><br>
    <input type="submit" name="uploadprofileimg" value="upload image">
</form>