<?php
    session_start();
    require_once('./twitteroauth/autoload.php');
    
    use Abraham\TwitterOAuth\TwitterOAuth;

    $ConsumerKey = "m8fmmQGoGbRpooxPGwgGg";
    $ConsumerSecret = "Llbr5TBIL0VcxZNS4jcIGXOq3qelCADnthYfjUeUQs";
    $AccessToken = $_SESSION['access_token'];
    
    $connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret,$AccessToken['oauth_token'],$AccessToken['oauth_token_secret']);

    $search_tweet = $connection -> get('search/tweets',array('q' => $_GET['search_word'],'count' => 10));

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php echo $_GET['search_word'];?>の検索結果</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<header>
    <div id="title">
        <h1>Title area.</h1>
    </div>
</header>

<body>
    <section class="search">
<a href="main.php">タイムラインに戻る</a>
<br>
    <h2>"<?php echo $_GET['search_word']; ?>"のTwitter検索結果</h2>
    <?php
    //*******debug mode*********
    //echo "debug mode<br><br>"; print_r($search_tweet);
    //**************************
    
        $count = sizeof($search_tweet->{"statuses"});
        for($Tweet_num = 0; $Tweet_num < $count; $Tweet_num++){
            $TweetID = $search_tweet->{"statuses"}[$Tweet_num]->{"id"};
            $Date = $search_twee->{"statuses"}[$Tweet_num]->{"created_at"};
            $Text = $search_tweet->{"statuses"}[$Tweet_num]->{"text"};
            $User_ID = $search_tweet->{"statuses"}[$Tweet_num]->{"user"}->{"screen_name"};
            $User_Name = $search_tweet->{"statuses"}[$Tweet_num]->{"user"}->{"name"};
            $Profile_image_URL = $search_tweet->{"statuses"}[$Tweet_num]->{"user"}->{"profile_image_url_https"};
            $Retweet_Count = $search_tweet->{"statuses"}[$Tweet_num]->{"retweet_count"};
            $Favorite_Count = $search_tweet->{"statuses"}[$Tweet_num]->{"favorite_count"};
        ?>
            <ul>
                <li>Profile_image : <img src = <?php echo $Profile_image_URL; ?>></li>
                <li>User Name : <?php echo $User_Name; ?></li>
                <li>User ID : @<?php echo $User_ID; ?></li>
                <li>Date : <?echo $Date; ?></li>
                <li>TweetID : <?php echo $TweetID; ?></li>
                <li>Tweet : <?php echo $Text; ?></li>
                <li>Retweet : <?php echo $Retweet_Count; ?></li>
                <li>Favorite : <?php echo $Favorite_Count; ?></li>
            </ul>
    <?php
        }
    ?>
    </section>
</body>
<footer>
    <div id="title">
        <h1>footer area.</h1>
    </div>
</footer>
</html>