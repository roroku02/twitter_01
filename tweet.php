<?php
    session_start();
    require_once('./twitteroauth/autoload.php');

    use Abraham\TwitterOAuth\TwitterOAuth;

    $ConsumerKey = "m8fmmQGoGbRpooxPGwgGg";
    $ConsumerSecret = "Llbr5TBIL0VcxZNS4jcIGXOq3qelCADnthYfjUeUQs";
    $AccessToken = $_SESSION['access_token'];

    $connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret,$AccessToken['oauth_token'],$AccessToken['oauth_token_secret']);

    $upload_media1 = './images/キャプチャ.png';
    $upload_media2 = [];
    $upload_media3 = [];
    $upload_media4 = [];
    $media_ids=[];
    if(isset($upload_media1)){
        $parameter1 = $connection->upload('media/upload',['media' => $upload_media1]);
        $media_ids = $parameter1 -> {"media_id_string"};
    }
    echo $media_ids;

    //$Tweet = $_POST['Tweet'];
    $Tweet = "@tos";
    $connection->post('statuses/update', ['status' => $Tweet,'media_ids' => $media_ids]);
    
    //header('location: ./main.php');
?>