<?php 
    include('./classes/DB.php');
    include('./classes/Login.php');
    
    $username="";
    //set within the queries later, if problems occur
    $isFollowing = False;

    if(isset($_GET['username'])){
        if(DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))){
            $username = DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
            
            $user_id = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
            $follower_id = Login::isLoggedIn();

            if($user_id != $follower_id){
                if(isset($_POST['follow'])){
                    if (!DB::query('SELECT follower_id FROM followers WHERE user_id=:user_id', array(':user_id'=>$user_id))) {
                        DB::query('INSERT INTO followers VALUES (\'\', :user_id, :follower_id)', array(':user_id'=>$user_id, ':follower_id'=>$follower_id));
                    }else {
                        echo 'Already following!';
                    }
                    $isFollowing = True;
                }
            
                if(isset($_POST['unfollow'])){
                    if (DB::query('SELECT follower_id FROM followers WHERE user_id=:user_id', array(':user_id'=>$user_id))) {
                        DB::query('DELETE FROM followers WHERE user_id=:user_id AND follower_id=:follower_id', array(':user_id'=>$user_id, ':follower_id'=>$follower_id));
                    }
                    $isFollowing = False;
                }

                if (DB::query('SELECT follower_id FROM followers WHERE user_id=:user_id', array(':user_id'=>$user_id))){
                    //following...
                    $isFollowing = True;
                }
            }
        }else{
            die('user_not_found');
        }
    }

?>
<h1><?php echo $username;?>'s Profile</h1>
<form action="profile.php?username=<?php echo $username; ?>" method="post">
    <?php
        if($user_id != $follower_id){
            if($isFollowing){
                echo '<input type="submit" value="unfollow" name="unfollow">';
            }else{
            echo '<input type="submit" value="follow" name="follow">';
            }
        }
    ?>
</form>