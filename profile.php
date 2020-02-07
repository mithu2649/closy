<?php 
    include('./classes/DB.php');
    include('./classes/Login.php');
    
    $username="";
    //set within the queries later, if problems occur
    $isFollowing = False;
    $isVerified = False;

    if(isset($_GET['username'])){
        if(DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))){
            $username = DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
            $isVerified = DB::query('SELECT verified FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['verified'];

            $user_id = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
            $follower_id = Login::isLoggedIn();

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
                $postbody = $_POST['postbody'];
                $user_id = Login::isLoggedIn();

                if(strlen($postbody)<1){
                    die('incorrect_length');
                }
                DB::query('INSERT INTO posts VALUES (\'\', :postbody, NOW(), :user_id, 0)', array(':postbody'=>$postbody, ':user_id'=>$user_id));

            }

            if(isset($_GET['postid'])){
                if(!DB::query('SELECT user_id FROM post_likes WHERE post_id=:post_id AND user_id=:user_id', array(':post_id'=>$_GET['postid'], ':user_id'=>$user_id))){
                    DB::query('UPDATE posts SET likes=likes+1 WHERE id=:postid', array(':postid'=>$_GET['postid']));
                    DB::query('INSERT INTO post_likes VALUES(\'\', :post_id, :user_id)', array(':post_id'=>$_GET['postid'], ':user_id'=>$user_id));    
                }else{
                    echo 'already liked!';
                }
            }

            $dbposts = DB::query('SELECT * FROM posts WHERE user_id=:user_id ORDER BY id DESC', array(':user_id'=>$user_id));
            $posts = "";
            foreach($dbposts as $p){
                $posts .= $p['body']."
                <form action='profile.php?username=$username&postid=".$p['id']."' method='post'>
                    <input type='submit' name='like' value='Like'>
                </form>
                <hr>";
            }
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