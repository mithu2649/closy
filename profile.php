<?php 
    include('./classes/DB.php');
    include('./classes/Login.php');
    
    $username="";
    //set within the queries later, if problems occur

    if(isset($_GET['username'])){
        if(DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))){
            $username = DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
           
            if(isset($_POST['follow'])){

                $user_id = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
                $follower_id = Login::isLoggedIn();


                if (!DB::query('SELECT follower_id FROM followers WHERE user_id=:user_id', array(':user_id'=>$user_id))) {
                    DB::query('INSERT INTO followers VALUES (\'\', :user_id, :follower_id)', array(':user_id'=>$user_id, ':follower_id'=>$follower_id));
                }else {
                    echo 'Already following!';
                }
            }
        }else{
            die('user_not_found');
        }
    }

?>
<h1><?php echo $username;?>'s Profile</h1>
<form action="profile.php?username=<?php echo $username; ?>" method="post">
    <input type="submit" value="follow" name="follow">
</form>