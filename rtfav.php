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

    //RT
    if(isset($_POST['rt'])){
        $res = $connection->post('statuses/retweet',['id' => $_POST['rt']]);
    }
    //Favo
    if(isset($_POST['fav'])){
        $res = $connection->post('favorites/create',['id' => $_POST['fav']]);
    }
    header('location: ./main.php');
?>