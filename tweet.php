<?php
    session_start();
    require_once('./twitteroauth/autoload.php');
    define("UPLOAD_IMAGE", "images/upload/");
    
    ini_set('display_errors', "On");
    ini_set('error_reporting', E_ALL);

    use Abraham\TwitterOAuth\TwitterOAuth;

    $ConsumerKey = "m8fmmQGoGbRpooxPGwgGg";
    $ConsumerSecret = "Llbr5TBIL0VcxZNS4jcIGXOq3qelCADnthYfjUeUQs";
    $AccessToken = $_SESSION['access_token'];

    $connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret,$AccessToken['oauth_token'],$AccessToken['oauth_token_secret']);

    $Tweet = htmlspecialchars($_POST['Tweet']);     //ツイートエスケープ処理

    //print_r($_FILES['upload_image']);
    //$upload_media1 = './images/test.PNG';
    if(($_FILES['upload_image']['size'][0]) != 0){
        for($i = 0; $i < count($_FILES['upload_image']['name']); $i++){
            //var_dump($_FILES['upload_image']['name'][$i]);
            //画像の拡張子を取得
            $file_ext = substr($_FILES['upload_image']['name'][$i],strrpos($_FILES['upload_image']['name'][$i],'.') + 1);
            //画像をimage＋拡張子に名前変更
            move_uploaded_file($_FILES['upload_image']['tmp_name'][$i],UPLOAD_IMAGE . 'image' . $i . '.' . $file_ext);
            $upload_media[$i] = UPLOAD_IMAGE . 'image' . $i . '.' . $file_ext;
        //var_dump($upload_media1);
            $parameter[$i] = $connection->upload('media/upload',['media' => $upload_media[$i]]);
            $media_ids[$i] = $parameter[$i] -> {"media_id_string"};
            //var_dump($parameter[$i]);
        //echo $media_ids;
        }

        $parameters = [
            'status' => $Tweet,
            'media_ids' => implode(',', $media_ids)
        ];
    }else{
        $parameters = [
            'status' => $Tweet
        ];
    }

    $connection->post('statuses/update', $parameters);
    header('location: ./main.php');
?>