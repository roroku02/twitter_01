<?php
    session_start();
    require_once('./twitteroauth/autoload.php');

    use Abraham\TwitterOAuth\TwitterOAuth;

    $ConsumerKey = "m8fmmQGoGbRpooxPGwgGg";
    $ConsumerSecret = "Llbr5TBIL0VcxZNS4jcIGXOq3qelCADnthYfjUeUQs";
    $AccessToken = $_SESSION['access_token'];

    $connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret,$AccessToken['oauth_token'],$AccessToken['oauth_token_secret']);

    $connection->post('statuses/update', ['status' => $_POST['Tweet']]);
    
    header('location: ./main.php');
?>