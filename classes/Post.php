<?php
    class Post{
        public static function createPost($postbody, $loggedInUserId, $profileUserId){
            if(strlen($postbody)<1){
                die('incorrect_length');
            }

            if($loggedInUserId == $profileUserId){
                DB::query('INSERT INTO posts VALUES (\'\', :postbody, NOW(), :user_id, 0, \'\')', array(':postbody'=>$postbody, ':user_id'=>$profileUserId));
            }else{
                die('incorrect_user : You must be logged in as the same user');
            }
        }

        public static function createImagePost($postbody, $loggedInUserId, $profileUserId){
            
            if($loggedInUserId == $profileUserId){
                
                DB::query('INSERT INTO posts VALUES (\'\', :postbody, NOW(), :user_id, 0, \'\')', array(':postbody'=>$postbody, ':user_id'=>$profileUserId));
                $post_id = DB::query('SELECT id FROM posts WHERE user_id = :user_id ORDER BY id DESC LIMIT 1', array(':user_id'=>$loggedInUserId))[0]['id'];
                return $post_id;
            }else{
                die('incorrect_user : You must be logged in as the same user');
            }
        }

        public static function likePost($post_id, $liker_id){
            if(!DB::query('SELECT user_id FROM post_likes WHERE post_id=:post_id AND user_id=:user_id', array(':post_id'=>$post_id, ':user_id'=>$liker_id))){
                DB::query('UPDATE posts SET likes=likes+1 WHERE id=:postid', array(':postid'=>$post_id));
                DB::query('INSERT INTO post_likes VALUES(\'\', :post_id, :user_id)', array(':post_id'=>$post_id, ':user_id'=>$liker_id));    
            }else{
                DB::query('UPDATE posts SET likes=likes-1 WHERE id=:postid', array(':postid'=>$post_id));
                DB::query('DELETE FROM post_likes WHERE post_id=:post_id AND user_id=:user_id', array(':post_id'=>$post_id, ':user_id'=>$liker_id));  
            }
        }

        public static function displayPosts($user_id, $username, $loggedInUserId){
            $dbposts = DB::query('SELECT * FROM posts WHERE user_id=:user_id ORDER BY id DESC', array(':user_id'=>$user_id));
            $posts = "";
            foreach($dbposts as $p){

                $likeButtonText = "Like";
                if(!DB::query('SELECT post_id FROM post_likes WHERE post_id=:post_id AND user_id=:user_id', array('post_id'=>$p['id'], ':user_id'=>$loggedInUserId))){
                    $likeButtonText = "Like";
                }else{
                    $likeButtonText = "Unike";
                }

                $posts .= $p['body']."
                <form action='profile.php?username=$username&postid=".$p['id']."' method='post'>
                    <input type='submit' name='$likeButtonText' value='$likeButtonText'>
                </form>
                <span>".$p['likes']." likes</span>
                <hr>";
            }

            return $posts;
        }
    }
?>

