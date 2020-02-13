<?php
    class Image{
        public static function uploadImage($form_name, $query, $params){
            $image = base64_encode(file_get_contents($_FILES[$form_name]['tmp_name']));

            $options = array('http'=>array(
                'method'=>"POST",
                'header'=>"Authorization: Bearer a8b246696dd2b4b1fd26343b4b42ec5e321bbff0\n".
                "Content-Type: application/x-www-form-urlencoded",
                'content'=>$image
            ));
    
            $context= stream_context_create($options);
            $imgurURL = "https://api.imgur.com/3/image";
    
    
            if($_FILES[$form_name]['size'] > 10240000){
                die('image_too_large: must be less than 10MB');
            }
    
            $response = file_get_contents($imgurURL, false, $context);
            $response = json_decode($response);
    
            //For debugging response from imgur server
            // echo '<pre>';
            // print_r($response);
            // echo '</pre>';
    
            $preparams = array($form_name=>$response->data->link);
            $params = $preparams + $params;

            DB::query($query, $params);
    
            
        }
    }
?>