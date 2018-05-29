<?php
session_start();
require_once('./twitteroauth/autoload.php');

use Abraham\TwitterOAuth\TwitterOAuth;

$ConsumerKey = "m8fmmQGoGbRpooxPGwgGg";
$ConsumerSecret = "Llbr5TBIL0VcxZNS4jcIGXOq3qelCADnthYfjUeUQs";
$AccessToken = $_SESSION['access_token'];

$connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret,$AccessToken['oauth_token'],$AccessToken['oauth_token_secret']);

$tweet = "";
?>


<!DOCTYPE html>

<head>
    <meta charset="utf-8" />
    <title>jisaku Twitter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<header>
    <div id="title">
        <h1>Title Area</h1>
    </div>
</header>

<body>
    <section class="Tweet">
        <h1>Tweet</h1>
        <form action="tweet.php" method="post">
            <textarea name="Tweet" id="Tweet" cols="100" rows="3" placeholder="今どうしてる？"></textarea>
            <input type="submit" value="Tweet">
        </form>
    </div>

    <section class="TimeLine">
    <h1>Twitter HOME TIMELINE</h1>
    
    <?php
    $home = $connection->get('statuses/home_timeline',array('count'=>10));
    
    print_r($home);
    $count = sizeof($home);
    for($Tweet_num = 0; $Tweet_num < $count; $Tweet_num++){
        $TweetID = $home[$Tweet_num]->{"id"};
        $Date = $home[$Tweet_num]->{"created_at"};
        $Text = $home[$Tweet_num]->{"text"};
        $User_ID = $home[$Tweet_num]->{"user"}->{"screen_name"};
        $User_Name = $home[$Tweet_num]->{"user"}->{"name"};
        $Profile_image_URL = $home[$Tweet_num]->{"user"}->{"profile_image_url_https"};
        $Retweet_Count = $home[$Tweet_num]->{"retweet_count"};
        $Favorite_Count = $home[$Tweet_num]->{"favorite_count"};
    ?>
        <ul>
            <li>Profile_image : <img src =<?php echo $Profile_image_URL; ?>></li>
            <li>User Name : <?php echo $User_Name ?></li>
            <li>User ID : @<?php echo $User_ID ?></li>
            <li>Date : <?echo $Date ?></li>
            <li>TweetID : <?php echo $TweetID ?></li>
            <li>Tweet : <?php echo $Text ?></li>
            <li>Retweet : <?php echo $Retweet_Count; ?></li>
            <li>Favorite : <?php echo $Favorite_Count; ?></li>
        </ul>
    <?php
        }
    ?>
    </section>

    <section class="search">
        <h1>Search</h1>
        <form action="search.php" method="get">
            <input type="text" name="search_word" placeholder="キーワード検索">
            <input type="submit" value="検索">
        </form>
    </section>

</body>


<footer>
    <div id="title">
        <h1>fotter area.</h1>
    </div>
</footer>

</html>