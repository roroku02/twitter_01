<?php
session_start();
require_once('./twitteroauth/autoload.php');

use Abraham\TwitterOAuth\TwitterOAuth;

$ConsumerKey = "m8fmmQGoGbRpooxPGwgGg";
$ConsumerSecret = "Llbr5TBIL0VcxZNS4jcIGXOq3qelCADnthYfjUeUQs";
$AccessToken = $_SESSION['access_token'];

$connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret,$AccessToken['oauth_token'],$AccessToken['oauth_token_secret']);

?>
<!DOCTYPE html>

<head>
    <meta charset="utf-8" />
    <title>jisaku Twitter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="stylesheet" type="text/css" href="css/lightbox.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="js/lightbox.js"></script>
</head>
<script>
    lightbox.option ({
        'alwaysShowNavOnTouchDevices': true,
        'fadeDuration': 200,
        'resizeDuration': 400
    })
</script>
<header>
    <div id="title">
        <h1><i class="fab fa-twitter"></i></h1>
    </div>
</header>
    
    <section>
    <?php
    $list = $connection -> get('lists/list');
    
    $list_Count = sizeof($list);
    for($list_num = 0;$list_num < $list_Count;$list_num++){
        $list_ID[] = $list[$list_num]->{"id"};
        $list_name[] = $list[$list_num]->{"slug"};
    }
    $list_ID = array_merge($list_ID,$list_name);
    print_r($list_ID);

    $list_statuses = $connection -> get('lists/statuses', array('list_id' => $list_ID[0],'count'=>50,'tweet_mode' => 'extended'));

    print_r($list_statuses);
    ?>
    </section>

<?php
    $count = sizeof($list_statuses);
    for($Tweet_num = 0; $Tweet_num < $count; $Tweet_num++){
        $TweetID = $list_statuses[$Tweet_num]->{"id"};
        $Date = $list_statuses[$Tweet_num]->{"created_at"};
        $Text = $list_statuses[$Tweet_num]->{"full_text"};
        $User_ID = $list_statuses[$Tweet_num]->{"user"}->{"screen_name"};
        $User_Name = $list_statuses[$Tweet_num]->{"user"}->{"name"};
        $Profile_image_URL = $list_statuses[$Tweet_num]->{"user"}->{"profile_image_url_https"};
        $Retweet_Count = $list_statuses[$Tweet_num]->{"retweet_count"};
        $Favorite_Count = $list_statuses[$Tweet_num]->{"favorite_count"};
        $Retweet_TRUE = FALSE;

        //RT処理
        if(isset($list_statuses[$Tweet_num]->{"retweeted_status"})){
            $Retweet_TRUE = TRUE;
            $Date = $list_statuses[$Tweet_num]->{"retweeted_status"}->{"created_at"};
            $RT_User = $User_Name;
            $Text = $list_statuses[$Tweet_num]->{"retweeted_status"}->{"full_text"};
            $User_ID = $list_statuses[$Tweet_num]->{"retweeted_status"}->{"user"}->{"screen_name"};
            $User_Name = $list_statuses[$Tweet_num]->{"retweeted_status"}->{"user"}->{"name"};
            $Profile_image_URL = $list_statuses[$Tweet_num]->{"retweeted_status"}->{"user"}->{"profile_image_url_https"};
            if(isset($list_statuses[$Tweet_num]->{"retweeted_status"}->{"entities"}->{"hashtags"}));
                $list_statuses[$Tweet_num]->{"entities"}->{"hashtags"} = $list_statuses[$Tweet_num]->{"retweeted_status"}->{"entities"}->{"hashtags"};
        }

        //ハッシュタグ処理
        $list_statuses[$Tweet_num]->{"entities"}->{"hashtags"} = array_reverse($list_statuses[$Tweet_num]->{"entities"}->{"hashtags"});
        foreach($list_statuses[$Tweet_num]->{"entities"}->{"hashtags"} as $hashtags){
            if(isset($hashtags)){
                $hashtag_text = $hashtags->text;
                $hashtag_indices = $hashtags->indices;
                $left_text = mb_substr($Text,0,$hashtag_indices[0]);
                $right_text = mb_substr($Text,($hashtag_indices[0] + ($hashtag_indices[1] - $hashtag_indices[0])));
                $after_text = '<a href="http://localhost/twitter_01/search.php?search_word=' . rawurlencode("#" . $hashtag_text) . '">#' . $hashtag_text . '</a>';
                $Text = $left_text . $after_text . $right_text;
            }
        }

        //URL処理
        if(isset($list_statuses[$Tweet_num]->{"entities"}->{"urls"})){
            foreach($list_statuses[$Tweet_num]->{"entities"}->{"urls"} as $urls){
                $Text = str_replace($urls->url,'<a href="'.$urls->expanded_url.'" target="_brank">'.$urls->display_url.'</a>',$Text);
            }
        }

        //画像処理
        $media_TRUE = FALSE;
        if(isset ($list_statuses[$Tweet_num]->{"entities"}->{"media"})){
            $media_TRUE = TRUE;
            $media_Count = sizeof($list_statuses[$Tweet_num]->{"extended_entities"}->{"media"});
            $media = [];
            for($media_num = 0;$media_num < $media_Count;$media_num++) {
                $media[$media_num] = $list_statuses[$Tweet_num]->{"extended_entities"}->{"media"}[$media_num]->{"media_url_https"};
            }
        }
    ?>

        <!-- 出力 -->
        <ul <?php /*RTカラー変更*/ if($Retweet_TRUE == TRUE) echo 'style = "border: 2px solid blue;"'?>>
            <?php if($Retweet_TRUE == TRUE){ ?>
            <p class="retweet_sentence"><i class="fas fa-retweet fa-fw"></i><?php echo $RT_User; ?>がリツイート</p> <?php } ?>
            <div id = "User_info">
                <li><img src =<?php echo $Profile_image_URL; ?>></li>
                <li id = "User_Name"><?php echo $User_Name ?></li>
                <li id = "User_ID">@<?php echo $User_ID ?></li>
            </div>
            <li><?echo $Date ?></li>
            <li><?php echo nl2br($Text); ?></li>
            <?php if($media_TRUE == TRUE){ ?>
                <li><?php for($media_num = 0;$media_num < $media_Count;$media_num++) { ?>
                    <a href="<?php echo $media[$media_num]; ?>" class="img" data-lightbox="group<?php echo $Tweet_num; ?>" style="background-image: url(<?php echo $media[$media_num]; ?>);"></a><?php } ?></li>
                    <?php } ?>
            <div id = "RT_Counter">
                <li><i class="fas fa-retweet fa-fw" style="color: green;"></i><?php echo $Retweet_Count; ?></li>
                <li><i class="fas fa-heart" style="color: red;"></i> <?php echo $Favorite_Count; ?></li>
            </div>
        </ul>
    <?php
        }
    ?>
    </section>