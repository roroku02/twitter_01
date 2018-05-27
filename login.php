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

    $URL = $connetion->url('oauth/authenticate',array('oauth_token'=>$request_token['oauth_token']));
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Login Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="style.css" />
</head>
<body>
    <section class="login">
        <h1>Welcome</h1>
        <a href="<?php echo $URL; ?>">ログイン</a>
    </section>
</body>
</html>