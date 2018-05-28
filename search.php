<?php
    session_start();
    require_once('./twitteroauth/autoload.php');
    
    use Abraham\TwitterOAuth\TwitterOAuth;

    $ConsumerKey = "m8fmmQGoGbRpooxPGwgGg";
    $ConsumerSecret = "Llbr5TBIL0VcxZNS4jcIGXOq3qelCADnthYfjUeUQs";
    $AccessToken = $_SESSION['access_token'];
    
    $connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret,$AccessToken['oauth_token'],$AccessToken['oauth_token_secret']);

    $search_tweet = $connection -> get('search/tweets',array('q' => $_GET['search_word'],'count' => 3));

    print_r($search_tweet);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php echo $_GET['search_word'];?>の検索結果</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<br><br><br>
debug
<br><br><br>
    <?php
        $count = sizeof($search_tweet);
        echo $search_tweet->{"statuses"}[0]->{"text"};
        for($Tweet_num = 0; $Tweet_num < $count; $Tweet_num++){
            $TweetID = $search_tweet->statuses[$Tweet_num]->{"id"};
            $Date = $search_tweet[$Tweet_num]->{"statuses"}->{"created_at"};
            $Text = $search_tweet[$Tweet_num]->{"text"};
            $User_ID = $search_tweet[$Tweet_num]->{"user"}->{"screen_name"};
            $User_Name = $search_tweet[$Tweet_num]->{"user"}->{"name"};
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
</html>