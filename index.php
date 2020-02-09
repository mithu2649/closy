<?php 
    include('classes/DB.php');
    include('classes/Login.php');
    include('classes/Post.php');

    $showTimeline = False;
    if(Login::isLoggedIn()){
        $user_id = Login::isLoggedIn();
        $showTimeline = True;
    }else{
        echo 'not_logged_in';
    }

    if(isset($_GET['postid'])){
        Post::likePost($_GET['postid'], $user_id);
    }

    $followingPosts = DB::query(
    'SELECT posts.id, posts.body, posts.likes, users.username 
    FROM users, posts, followers
    WHERE posts.user_id = followers.user_id 
    AND users.id = posts.user_id
    AND follower_id = 3
    ORDER BY posts.likes DESC;');

    foreach($followingPosts as $post){
        $likeButtonText = "Like";
        if(!DB::query('SELECT post_id FROM post_likes WHERE post_id=:post_id AND user_id=:user_id', array('post_id'=>$post['id'], ':user_id'=>$user_id))){
            $likeButtonText = "Like";
        }else{
            $likeButtonText = "Unike";
        }

        echo $post['body'].' ~ '.$post['username'];
        echo "<form action='index.php?postid=".$post['id']."' method='post'>
                    <input type='submit' name='$likeButtonText' value='$likeButtonText'>
             </form>
            <span>".$post['likes']." likes</span>
            <hr>";
    }
    
?>