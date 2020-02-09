<?php 
    include('classes/DB.php');
    include('classes/Login.php');
    $showTimeline = False;
    if(Login::isLoggedIn()){
        echo 'logged_in with userID: '.Login::isLoggedIn().'<br><br>';
        $showTimeline = True;
    }else{
        echo 'not_logged_in';
    }

    $followingPosts = DB::query(
    'SELECT posts.body, posts.likes, users.username 
    FROM users, posts, followers
    WHERE posts.user_id = followers.user_id 
    AND users.id = posts.user_id
    AND follower_id = 3
    ORDER BY posts.likes DESC;');

    foreach($followingPosts as $post){
        echo $post['body'].' ~ '.$post['username'].'<hr>';
    }
    
?>