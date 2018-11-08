<?php
    session_start();
    require_once('./twitteroauth/autoload.php');
    define("UPLOAD_IMAGE", "images/upload/");

    use Abraham\TwitterOAuth\TwitterOAuth;

    $ConsumerKey = "m8fmmQGoGbRpooxPGwgGg";
    $ConsumerSecret = "Llbr5TBIL0VcxZNS4jcIGXOq3qelCADnthYfjUeUQs";
    $AccessToken = $_SESSION['access_token'];

    $connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret,$AccessToken['oauth_token'],$AccessToken['oauth_token_secret']);

    //$upload_media1 = './images/test.PNG';
    //画像の拡張子を取得
    $file_ext = substr($_FILES['upload_image']['name'],strrpos($_FILES['upload_image']['name'],'.') + 1);
    //echo $file_ext;
    //画像をimage＋拡張子に名前変更
    move_uploaded_file($_FILES['upload_image']['tmp_name'],UPLOAD_IMAGE . 'image' . '.' . $file_ext);
    $upload_media1 = UPLOAD_IMAGE . 'image.' . $file_ext;
    //var_dump($upload_media1);
    echo $upload_media1;
    echo "<img src= $upload_media1 >";
    $upload_media2 = [];
    $upload_media3 = [];
    $upload_media4 = [];
    //$parameter1 = $connection->upload('media/upload',['media' => $upload_media1]);
    //$media_ids = $parameter1 -> {"media_id_string"};
    //var_dump($parameter1);
    //echo $media_ids;

    $Tweet = $_POST['Tweet'];
    //$connection->post('statuses/update', ['status' => $Tweet,'media_ids' => $media_ids]);
    
?>
<a href="./main.php">戻る</a>