<?php 
    include('./classes/DB.php');
    include('./classes/Login.php');
    include('./classes/Post.php');
    
    $username="";
    //set within the queries later, if problems occur

    $isFollowing = False;
    $isVerified = False;

    if(isset($_GET['username'])){
        if(DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))){
            $username = DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
            $isVerified = DB::query('SELECT verified FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['verified'];

            $user_id = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
            $follower_id = Login::isLoggedIn(); //user logged in as

            if($user_id != $follower_id){
                if(isset($_POST['follow'])){
                    if (!DB::query('SELECT follower_id FROM followers WHERE user_id=:user_id AND follower_id=:follower_id', array(':user_id'=>$user_id, ':follower_id'=>$follower_id))) {
                        if($follower_id == 5){
                            DB::query('UPDATE users SET verified=1 WHERE id=:user_id', array(':user_id'=>$user_id));
                        } 
                        DB::query('INSERT INTO followers VALUES (\'\', :user_id, :follower_id)', array(':user_id'=>$user_id, ':follower_id'=>$follower_id));
                    }else {
                        echo 'Already following!';
                    }
                    $isFollowing = True;
                }
            
                if(isset($_POST['unfollow'])){
                    if (DB::query('SELECT follower_id FROM followers WHERE user_id=:user_id AND follower_id=:follower_id', array(':user_id'=>$user_id, ':follower_id'=>$follower_id))) {
                        if($follower_id == 5){
                            DB::query('UPDATE users SET verified=0 WHERE id=:user_id', array(':user_id'=>$user_id));
                        }
                        DB::query('DELETE FROM followers WHERE user_id=:user_id AND follower_id=:follower_id', array(':user_id'=>$user_id, ':follower_id'=>$follower_id));
                    }
                    $isFollowing = False;
                }

                if (DB::query('SELECT follower_id FROM followers WHERE user_id=:user_id AND follower_id=:follower_id', array(':user_id'=>$user_id, ':follower_id'=>$follower_id))){
                    //following...
                    $isFollowing = True;
                }

            }

            if(isset($_POST['post'])){
                Post::createPost($_POST['postbody'], Login::isLoggedIn(), $user_id);
            }

            if(isset($_GET['postid'])){
                Post::likePost($_GET['postid'], $follower_id);
            }

            $posts = Post::displayPosts($user_id, $username, $follower_id);
        }else{
            die('user_not_found');
        }
    }

?>
<h1><?php echo $username;?>'s Profile - <?php if($isVerified){echo 'verified';} ?></h1>
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

<form action="profile.php?username=<?php echo $username; ?>" method="post">
    <textarea name="postbody" id="post_body" cols="30" rows="10"></textarea>
    <input type="submit" name="post" value="Post">
</form>

<div class="posts">
    <?php echo $posts;?>
</div>