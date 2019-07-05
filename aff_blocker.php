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

    //ユーザ情報を取得
    $user_profile_info = $connection -> get('account/verify_credentials');
    $user_name = $user_profile_info -> {'screen_name'};
    $user_id = $user_profile_info -> {'id'};

    //フォロワーを取得
    $follower_list = $connection -> get('followers/list',array('user_id' => $user_id,'count' => 10));
    //print_r($follower_list);
    foreach($follower_list -> {"users"} as $f){
        $f_user{'name'}[] = $f -> {"name"};
        $f_user{'description'}[] = $f -> {'description'};
    }

    print_r($f_user);