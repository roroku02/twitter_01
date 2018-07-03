<?php
    session_start();
    require_once('./twitteroauth/autoload.php');
    
    use Abraham\TwitterOAuth\TwitterOAuth;

    $ConsumerKey = "m8fmmQGoGbRpooxPGwgGg";
    $ConsumerSecret = "Llbr5TBIL0VcxZNS4jcIGXOq3qelCADnthYfjUeUQs";
    $AccessToken = $_SESSION['access_token'];
    
    $connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret,$AccessToken['oauth_token'],$AccessToken['oauth_token_secret']);

    if(isset($_GET['option'])){
        $tweet_sort = $_GET['option'];
    }else{
        $tweet_sort = "recent";
        $_SESSION['search_word'] = $_GET['search_word'];
    }

    $search_tweet = $connection -> get('search/tweets',array('q' => $_SESSION['search_word'] .' exclude:retweets','count' => 50,'tweet_mode' => 'extended', 'result_type' => $tweet_sort));
    $now_time = time();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php echo $_SESSION['search_word']; ?>の検索結果</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link href="http://fonts.googleapis.com/earlyaccess/sawarabigothic.css" rel="stylesheet" />
    <link href="http://fonts.googleapis.com/earlyaccess/mplus1p.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/colorbox.css">
    <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="js/lightbox.js"></script>
    <script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
    <script type="text/javascript" src="js/jquery.colorbox-ja.js"></script>
    <link rel="stylesheet" type="text/css" href="css/lightbox.css" />
    <script type="text/javascript" src="js/lightbox.js"></script>
</head>
<script>
    lightbox.option ({
        'alwaysShowNavOnTouchDevices': true,
        'fadeDuration': 200,
        'resizeDuration': 400
    })
    $(document).ready(function(){
      $(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
   });
</script>
</head>
<header>
    <div id="title">
        <h1><i class="fab fa-twitter"></i></h1>
    </div>
</header>

<body>
    <section class="search">
<a href="main.php">タイムラインに戻る</a>
<br>
    <h2>"<?php echo $_SESSION['search_word']; ?>"のTwitter検索結果</h2>
    <?php
    //*******debug mode*********
    //echo "debug mode<br><br>"; print_r($search_tweet);
    //**************************

   ?>
   <h2>並び替え</h2>
   <form action="search.php" method="get">
       <input type="radio" name="option" value="recent" onchange="this.form.submit()">新しい順</input>
       <input type="radio" name="option" value="popular" onchange="this.form.submit()">人気度順</input> 
    </form>
    <?php
    $count = sizeof($search_tweet->{"statuses"});
    for($Tweet_num = 0; $Tweet_num < $count; $Tweet_num++){
        $TweetID = $search_tweet->{"statuses"}[$Tweet_num]->{"id"};
        $Date = $search_tweet->{"statuses"}[$Tweet_num]->{"created_at"};
        $Tweet_time = strtotime($Date);
        $relative_time = $now_time - $Tweet_time;
        $Text = $search_tweet->{"statuses"}[$Tweet_num]->{"full_text"};
        $User_ID = $search_tweet->{"statuses"}[$Tweet_num]->{"user"}->{"screen_name"};
        $User_Name = $search_tweet->{"statuses"}[$Tweet_num]->{"user"}->{"name"};
        $Profile_image_URL = $search_tweet->{"statuses"}[$Tweet_num]->{"user"}->{"profile_image_url_https"};
        $Retweet_Count = $search_tweet->{"statuses"}[$Tweet_num]->{"retweet_count"};
        $Favorite_Count = $search_tweet->{"statuses"}[$Tweet_num]->{"favorite_count"};
        $Retweet_TRUE = FALSE;
        $media_URL = NULL;

        //RT処理
        if(isset($search_tweet->{"statuses"}[$Tweet_num]->{"retweeted_status"})){
            $Retweet_TRUE = TRUE;
            $Date = $search_tweet->{"statuses"}[$Tweet_num]->{"retweeted_status"}->{"created_at"};
            $RT_User = $User_Name;
            $Text = $search_tweet->{"statuses"}[$Tweet_num]->{"retweeted_status"}->{"full_text"};
            $User_ID = $search_tweet->{"statuses"}[$Tweet_num]->{"retweeted_status"}->{"user"}->{"screen_name"};
            $User_Name = $search_tweet->{"statuses"}[$Tweet_num]->{"retweeted_status"}->{"user"}->{"name"};
            $Profile_image_URL = $search_tweet->{"statuses"}[$Tweet_num]->{"retweeted_status"}->{"user"}->{"profile_image_url_https"};
            $Retweet_Count = $search_tweet->{"statuses"}[$Tweet_num]->{"retweeted_status"}->{"retweet_count"};
            $Favorite_Count = $search_tweet->{"statuses"}[$Tweet_num]->{"retweeted_status"}->{"favorite_count"};    
            if(isset($search_tweet->{"statuses"}[$Tweet_num]->{"retweeted_status"}->{"entities"}->{"hashtags"}));
                $search_tweet->{"statuses"}[$Tweet_num]->{"entities"}->{"hashtags"} = $search_tweet->{"statuses"}[$Tweet_num]->{"retweeted_status"}->{"entities"}->{"hashtags"};
            if(isset($search_tweet->{"statuses"}[$Tweet_num]->{"retweeted_status"}->{"extended_entities"}->{"media"})){
                foreach($search_tweet->{"statuses"}[$Tweet_num]->{"retweeted_status"}->{"extended_entities"}->{"media"} as $media){
                    $media_URL[] = $media->media_url_https;
                }
            }
        }

            //ハッシュタグ処理
            $search_tweet->{"statuses"}[$Tweet_num]->{"entities"}->{"hashtags"} = array_reverse($search_tweet->{"statuses"}[$Tweet_num]->{"entities"}->{"hashtags"});
            foreach($search_tweet->{"statuses"}[$Tweet_num]->{"entities"}->{"hashtags"} as $hashtags){
                if(isset($hashtags)){
                    $hashtag_text = $hashtags->text;
                    $hashtag_indices = $hashtags->indices;
                    $left_text = mb_substr($Text,0,$hashtag_indices[0]);
                    $right_text = mb_substr($Text,($hashtag_indices[0] + ($hashtag_indices[1] - $hashtag_indices[0])));
                    $after_text = '<a href="http://localhost/twitter_01/search.php?search_word=' . rawurlencode("#" . $hashtag_text) . '">#' . $hashtag_text . '</a>';
                    $Text = $left_text . $after_text . $right_text;
                }
            }
            if(isset($search_tweet->{"statuses"}[$Tweet_num]->{"extended_entities"}->{"media"})){
                foreach($search_tweet->{"statuses"}[$Tweet_num]->{"extended_entities"}->{"media"} as $media){
                    $media_URL[] = $media->media_url_https;
                }
            }
            if(isset($search_tweet->{"statuses"}[$Tweet_num]->{"entities"}->{"urls"})){
                foreach($search_tweet->{"statuses"}[$Tweet_num]->{"entities"}->{"urls"} as $urls){
                    $Text = str_replace($urls->url,'<a href="'.$urls->expanded_url.'" class= "iframe">'.$urls->display_url.'</a>',$Text);
                }
            }
            
        ?>
            <ul>
            <?php if($Retweet_TRUE == TRUE){ ?>
                <p class="retweet_sentence"><i class="fas fa-retweet fa-fw"></i><?php echo $RT_User; ?>がリツイート</p>
                <?php } ?>
            <div id = "Tweet_header">
                <div id = "User_info">
                    <li><img src =<?php echo $Profile_image_URL; ?>></li>
                    <li id = "User_Name"><?php echo $User_Name ?></li>
                    <li id = "User_ID">@<?php echo $User_ID ?></li>
                </div>
                <li><?php if($relative_time < 60){ 
                    echo $relative_time . "秒前";
                }elseif($relative_time >= 60 && $relative_time < (60 * 60)){
                    echo floor($relative_time / 60) . "分前";
                }elseif($relative_time >= (60 * 60) && $relative_time < (60 * 60 * 24)){
                    echo floor($relative_time / (60 * 60)) . "時間前";
                }elseif($relative_time >= (60 * 60 * 24)){
                    echo date("n月j日",$tweet_time);
                }?>
                </li>
            </div>
            <li><?php echo nl2br($Text); ?></li>
            <?php if(isset($media_URL)){ 
                $media_Count = sizeof($media_URL);?>
                <li><?php for($media_num = 0;$media_num < $media_Count;$media_num++) { ?>
                    <a href="<?php echo $media_URL[$media_num]; ?>" class="img" data-lightbox="group<?php echo $Tweet_num; ?>" style="background-image: url(<?php echo $media_URL[$media_num] .':small'; ?>);"></a><?php } ?></li>
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
</body>
<footer>
    <div id="title">
        <h1><a href="main.php">タイムラインに戻る</a></h1>
    </div>
</footer>
</html>