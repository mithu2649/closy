<?php
   
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
        $resposne = file_get_contents($imgurURL, false, $context);
    }
?>
<h1>My Account</h1>
<form action="my_account.php" method="post" enctype="multipart/form-data">
Upload an Image:
    <input type="file" name="profileimg"><br><br>
    <input type="submit" name="uploadprofileimg" value="upload image">
</form>