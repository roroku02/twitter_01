<?php
session_cache_limiter('private_no_expire');
session_start();
require_once('./twitteroauth/autoload.php');

use Abraham\TwitterOAuth\TwitterOAuth;

$ConsumerKey = "m8fmmQGoGbRpooxPGwgGg";
$ConsumerSecret = "Llbr5TBIL0VcxZNS4jcIGXOq3qelCADnthYfjUeUQs";
$AccessToken = "312682302-uvj5vhZYfgCYt75opEA7gnfDz7eaOvcNUUL3UgbL";
$AccessTokenSecret = "jPwIHW2tjE3GQVgbL3JHXzosOBcTKsUpFsWdjfMFyU8EI";
$CallBackUrl = "#";

$connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret,$AccessToken,$AccessTokenSecret);

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
        <form action="" method="post">
            <textarea name="Tweet" id="Tweet" cols="100" rows="3" placeholder="今どうしてる？"></textarea>
            <input type="submit" value="Tweet">
        </form>
    </div>

    <section class="TimeLine">
    <h1>Twitter HOME TIMELINE</h1>
    
    <?php
    $home = $connection->get('statuses/home_timeline',array('count'=>10));
    $count = sizeof($home);
    for($Tweet_num = 0; $Tweet_num < $count; $Tweet_num++){
        $TweetID = $home[$Tweet_num]->{"id"};
        $Date = $home[$Tweet_num]->{"created_at"};
        $Text = $home[$Tweet_num]->{"text"};
        $User_ID = $home[$Tweet_num]->{"user"}->{"screen_name"};
        $User_Name = $home[$Tweet_num]->{"user"}->{"name"};
    ?>
        <ul>
            <li>User Name : <?php echo $User_Name ?></li>
            <li>User ID : @<?php echo $User_ID ?></li>
            <li>Date : <?echo $Date ?></li>
            <li>TweetID : <?php echo $TweetID ?></li>
            <li>Tweet : <?php echo $Text ?></li>
        </ul>
    <?php
        }
    ?>
    </section>

</body>


<footer>
    <div id="title">
        <h1>fotter area.</h1>
    </div>
</footer>

</html>