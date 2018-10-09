<?php
    session_start();
    require_once('./twitteroauth/autoload.php');

    use Abraham\TwitterOAuth\TwitterOAuth;

    $ConsumerKey = "m8fmmQGoGbRpooxPGwgGg";
    $ConsumerSecret = "Llbr5TBIL0VcxZNS4jcIGXOq3qelCADnthYfjUeUQs";
    $AccessToken = $_SESSION['access_token'];

    $connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret,$AccessToken['oauth_token'],$AccessToken['oauth_token_secret']);

    $upload_media1 = './images/test.PNG';
    echo "<img src= $upload_media1 >";
    $upload_media2 = [];
    $upload_media3 = [];
    $upload_media4 = [];
    $parameter1 = $connection->upload('media/upload',['media' => './images/test.png']);
    $media_ids = $parameter1 -> {"media_id_string"};
    var_dump($parameter1);
    echo $media_ids;

    $Tweet = $_POST['Tweet'];
    $connection->post('statuses/update', ['status' => $Tweet,'media_ids' => $media_ids]);
    
?>
<a href="./main.php">戻る</a>