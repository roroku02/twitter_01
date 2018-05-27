<?php
    session_start();
    require_once('./twitteroauth/autoload.php');
    use Abraham\TwitterOAuth\TwitterOAuth;
 
    $ConsumerKey = "m8fmmQGoGbRpooxPGwgGg";
    $ConsumerSecret = "Llbr5TBIL0VcxZNS4jcIGXOq3qelCADnthYfjUeUQs";
    $Callback_URL = "http://localhost/twitter_01/callback.php";

    $connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret);

    $request_token = $connection->oauth('oauth/request_token', array('oauth_callback=>$CallbackURL'));

    $_SESSION['oauth_token'] = $request_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

    $Url = $connection->url('oauth/authorize',array('oauth_token' => $request_token['oauth_token']));
    
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<h1>Welcome</h1>
<p>ログイン画面に移動</p>
    <a href="<?php echo $Url; ?>">Login</a>
</body>
</html>