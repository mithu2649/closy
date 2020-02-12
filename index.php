<?php 
    include('classes/DB.php');
    include('classes/Login.php');
    include('classes/Post.php');
    include('classes/Comment.php');

    $showTimeline = False;
    if(Login::isLoggedIn()){
        $user_id = Login::isLoggedIn();
        $showTimeline = True;

        if(isset($_GET['postid'])){
            Post::likePost($_GET['postid'], $user_id);
        }
        if(isset($_POST['comment'])){
            Comment::createComment($_POST['commentbody'], $_GET['postid'], $user_id);
        }
    
        $followingPosts = DB::query(
        'SELECT posts.id, posts.body, posts.likes, users.username 
        FROM users, posts, followers
        WHERE posts.user_id = followers.user_id 
        AND users.id = posts.user_id
        AND follower_id = :user_id
        ORDER BY posts.likes DESC;', array(':user_id'=>$user_id));
    
        foreach($followingPosts as $post){
            $likeButtonText = "Like";
            if(!DB::query('SELECT post_id FROM post_likes WHERE post_id=:post_id AND user_id=:user_id', array('post_id'=>$post['id'], ':user_id'=>$user_id))){
                $likeButtonText = "Like";
            }else{
                $likeButtonText = "Unike";
            }
            
            echo '<hr>';
            echo $post['body'].' ~ '.$post['username'];
            echo "<br><br><form style='display:inline-block' action='index.php?postid=".$post['id']."' method='post'>
                        <input type='submit' name='$likeButtonText' value='$likeButtonText'>
                 </form>
                <span>".$post['likes']." likes</span>
                <br>
                <form action='index.php?postid=".$post['id']."' method='post'>
                    <textarea name='commentbody' id='commentbody' cols='30' rows='2 '></textarea>
                    <input type='submit' name='comment' value='Comment'>
                </form>
                ";  
                Comment::displayComments($post['id']);
        }

    }else{
        header('location:login.php');
    }
    
?>