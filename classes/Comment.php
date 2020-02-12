<?php
    class Comment{
        public static function createComment($commentbody, $post_id, $user_id){
            if(strlen($commentbody)<1){
                die('incorrect_length');
            }

           if(!DB::query('SELECT id FROM posts WHERE id=:post_id', array(':post_id'=>$post_id))){
               echo 'invalid_post_id';
           }else{
               DB::query('INSERT INTO comments VALUES(\'\', :comment, :user_id, NOW(), :postid)', array(':comment'=>$commentbody, ':user_id'=>$user_id, ':postid'=>$post_id));
           }
        }

        public static function displayComments($post_id){
            $comments = DB::query('SELECT comments.comment, users.username FROM comments, users 
            WHERE post_id=:post_id
            AND comments.user_id = users.id', array('post_id'=>$post_id));
            
            foreach($comments as $comment){
                echo $comment['username'].' -> '.$comment['comment'].'<br>';
            }
        }
    }
?>