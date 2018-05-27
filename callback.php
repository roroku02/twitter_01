<?php
    session_start();
    require_once('./twitteroauth/autoload.php');
    use Abraham\TwitterOAuth\TwitterOAuth;

    $ConsumerKey = "m8fmmQGoGbRpooxPGwgGg";
    $ConsumerSecret = "Llbr5TBIL0VcxZNS4jcIGXOq3qelCADnthYfjUeUQs";

    $request_token = [];
    $request_token['oauth_token'] = $_SESSION['oauth_token'];
    $request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];

    $connection = new TwitterOAuth($ConssumerKey,$ConsumerSecret,$request_token['oauth_token'],$request_token['oauth_token_secret']);

    $_SESSION['access_token'] = $connection->oauth("oauth/access_token",array("oauth_verifier"=>$_REQUEST['oauth_verifier']));

    session_regenerate_id();

    header('location: /main.php');
?>