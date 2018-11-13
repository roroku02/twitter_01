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
    <link rel="stylesheet" type="text/css" href="css/lightbox.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/colorbox.css">
    <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="js/lightbox.js"></script>
    <script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
    <script type="text/javascript" src="js/jquery.colorbox-ja.js"></script>
    <script type="text/javascript" src="js/lightbox.js"></script>
    <script type="text/javascript" src="js/pulltorefresh.js"></script>
</head>
<script>
    lightbox.option ({
        'alwaysShowNavOnTouchDevices': true,
        'fadeDuration': 200,
        'resizeDuration': 400
    })
    PullToRefresh.init({
        mainElement: 'body',
        onRefresh: function(){ window.location.reload(); }
    });
   $(document).ready(function(){
      $(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
   });
   function toggle(){
       $('.toggle-box').slideToggle('slow');
   }
   function msgpopup(){
       alert("現在ツイート機能を利用できません。");
   }
</script>
<header>
    <div id="title">
        <h1><i class="fab fa-twitter"></i></h1>
    </div>
    <div id="logout_button">
        <a href="./logout.php">ログアウト</a>
    </div>
</header>

<body>
    <section class="Tweet">
        <h1>Tweet</h1>
        <form action="tweet.php" method="post" enctype="multipart/form-data">
            <textarea name="Tweet" id="Tweet" cols="50" rows="3" placeholder="今どうしてる？"></textarea>
            <input type="file" name="upload_image[]" id="upload_image[]" multiple accept="image/*">
            <input type="submit" value="Tweet" class="Tweet_button"> <!-- type="submit" -->
        </form>
    </section>

    <section class="Trend">
        <?php $Trend_responce = $connection -> get('trends/place', array('id' => '1110809'));
        foreach($Trend_responce[0] -> {"trends"} as $Trend){
            $Trend_words[] = $Trend->name;
        };?>
        <a href="javascript:toggle()" class="toggle-button"><h1>トレンドワード<i class="fas fa-chevron-circle-down" style="padding-left:5px;"></i></h1></a>
        <ul class = toggle-box>
            <?php
            foreach($Trend_words as $Trend_word){
                echo '<a href="http://localhost/twitter_01/search.php?search_word='.$Trend_word.'">'.$Trend_word.'</a>';
                echo '<br />';
            };?>
        </ul>
    </section>

    <section class="list_option">
    <h1>リストタイムライン</h1>
    <?php
    $list = $connection -> get('lists/list');
    $list_Count = sizeof($list);
    for($list_num = 0;$list_num < $list_Count;$list_num++){
        $list_ID[] = $list[$list_num]->{"id"};
        $list_name[] = $list[$list_num]->{"slug"};
    }
    $list_lists = array($list_ID,$list_name);
    ?>
    <form action="list.php" method="get">
    <select name="list_id" onchange="this.form.submit()">
        <option selected>表示するリストを選択してください</option>
        <?php for($i = 0;$i < $list_Count;$i++){ ?>
            <option value= "<?php echo $list_ID[$i]; ?>"><?php echo $list_name[$i]; ?></option>
        <?php } ?>
    </select>
    </form>
    <h1>アニメタグ検索</h1>
    <form action="anime.php" method="get">
        <select name="year">
            <option value="2014">2014</option>
            <option value="2015">2015</option>
            <option value="2016">2016</option>
            <option value="2017">2017</option>
            <option value="2018">2018</option>
        </select>
        <select name="season">
            <option value="1">冬</option>
            <option value="2">春</option>
            <option value="3">夏</option>
            <option value="4">秋</option>
        </select>
        <input type="submit" value="実行">
    </form>
    </section>
    
    <section class="TimeLine">
    <h1>Twitter HOME TIMELINE</h1>
    
    <?php
    $home = $connection->get('statuses/home_timeline',array('count'=>50,'tweet_mode' => 'extended'));
    $now_time = time();
    
    //*******debug mode*********
    //echo "debug mode<br><br>"; print_r($home);
    //***************************

    $count = sizeof($home);
    for($Tweet_num = 0; $Tweet_num < $count; $Tweet_num++){
        $TweetID = $home[$Tweet_num]->{"id"};
        $Date = $home[$Tweet_num]->{"created_at"};
        $Tweet_time = strtotime($Date);
        $relative_time = $now_time - $Tweet_time;
        $Text = $home[$Tweet_num]->{"full_text"};
        $User_ID = $home[$Tweet_num]->{"user"}->{"screen_name"};
        $User_Name = $home[$Tweet_num]->{"user"}->{"name"};
        $Profile_image_URL = $home[$Tweet_num]->{"user"}->{"profile_image_url_https"};
        $Retweet_Count = $home[$Tweet_num]->{"retweet_count"};
        $Favorite_Count = $home[$Tweet_num]->{"favorite_count"};
        $Retweet_TRUE = FALSE;

        //RT処理
        if(isset($home[$Tweet_num]->{"retweeted_status"})){
            $Retweet_TRUE = TRUE;
            $Date = $home[$Tweet_num]->{"retweeted_status"}->{"created_at"};
            $RT_User = $User_Name;
            $Text = $home[$Tweet_num]->{"retweeted_status"}->{"full_text"};
            $User_ID = $home[$Tweet_num]->{"retweeted_status"}->{"user"}->{"screen_name"};
            $User_Name = $home[$Tweet_num]->{"retweeted_status"}->{"user"}->{"name"};
            $Profile_image_URL = $home[$Tweet_num]->{"retweeted_status"}->{"user"}->{"profile_image_url_https"};
            $Retweet_Count = $home[$Tweet_num]->{"retweeted_status"}->{"retweet_count"};
            $Favorite_Count = $home[$Tweet_num]->{"retweeted_status"}->{"favorite_count"};    
            if(isset($home[$Tweet_num]->{"retweeted_status"}->{"entities"}->{"hashtags"}));
                $home[$Tweet_num]->{"entities"}->{"hashtags"} = $home[$Tweet_num]->{"retweeted_status"}->{"entities"}->{"hashtags"};
        }

        //ハッシュタグ処理
        $home[$Tweet_num]->{"entities"}->{"hashtags"} = array_reverse($home[$Tweet_num]->{"entities"}->{"hashtags"});
        foreach($home[$Tweet_num]->{"entities"}->{"hashtags"} as $hashtags){
            if(isset($hashtags)){
                $hashtag_text = $hashtags->text;
                $hashtag_indices = $hashtags->indices;
                $left_text = mb_substr($Text,0,$hashtag_indices[0]);
                $right_text = mb_substr($Text,($hashtag_indices[0] + ($hashtag_indices[1] - $hashtag_indices[0])));
                $after_text = '<a href="http://localhost/twitter_01/search.php?search_word=' . rawurlencode("#" . $hashtag_text) . '" class= "iframe">#' . $hashtag_text . '</a>';
                $Text = $left_text . $after_text . $right_text;
            }
        }

        //URL処理
        if(isset($home[$Tweet_num]->{"entities"}->{"urls"})){
            foreach($home[$Tweet_num]->{"entities"}->{"urls"} as $urls){
                $Text = str_replace($urls->url,'<a href="'.$urls->expanded_url.'" class= "iframe">'.$urls->display_url.'</a>',$Text);
            }
        }

        //画像処理
        $media_TRUE = FALSE;
        if(isset ($home[$Tweet_num]->{"entities"}->{"media"})){
            $media_TRUE = TRUE;
            $media_Count = sizeof($home[$Tweet_num]->{"entities"}->{"media"});
            $media = [];
            for($media_num = 0;$media_num < $media_Count;$media_num++) {
                $media[$media_num] = $home[$Tweet_num]->{"entities"}->{"media"}[$media_num]->{"media_url_https"};
            }
        }elseif(isset($home[$Tweet_num]->{"retweeted_status"}->{"entities"}->{"media"})){
            $media_TRUE = TRUE;
            $media =[];
            for($media_num = 0;$media_num < sizeof($home[$media_num]->{"retweeted_status"}->{"entities"}->{"media"});$media_num++){
                $media[$media_num] = $home[$Tweet_num]->{"retweeted_status"}->{"entities"}->{"media"}->{"media_url_https"};
            }
        }

        $Verified_User = FALSE;
        if($home[$Tweet_num]->{"user"}->{"verified"} == "1"){
            $Verified_User = TRUE;
        }elseif($User_ID == "roroku02"){
            $Verified_User = TRUE;
        }
    ?>

        <!-- 出力 -->
        <ul>
            <?php if($Retweet_TRUE == TRUE){ ?>
            <p class="retweet_sentence"><i class="fas fa-retweet fa-fw"></i><?php echo $RT_User; ?>がリツイート</p> <?php } ?>
            <div id = "Tweet_header">
                <div id = "User_info">
                    <li><img src =<?php echo $Profile_image_URL; ?>></li>
                    <li id = "User_Name"><?php echo $User_Name ?></li>
                    <li id = "User_ID">@<?php echo $User_ID ?></li>
                    <?php if($Verified_User == TRUE){ ?>
                        <li id = "Verified_User" style="padding-left:5px;"><img src="./images/verified_account.png"></img></li>
                    <?php } ?>
                </div>
                    <li><?php if($relative_time < 60){ 
                        echo $relative_time . "秒前";
                    }elseif($relative_time >= 60 && $relative_time < (60 * 60)){
                        echo floor($relative_time / 60) . "分前";
                    }elseif($relative_time >= (60 * 60) && $relative_time < (60 * 60 * 24)){
                        echo floor($relative_time / (60 * 60)) . "時間前";
                    }elseif($relative_time >= (60 * 60 * 24)){
                        echo date("Y/n/j G:i",$Tweet_time);
                    }
                    ?></li>
            </div>
            <li><?php echo nl2br($Text); ?></li>
            <?php if($media_TRUE == TRUE){ ?>
                <li><?php for($media_num = 0;$media_num < $media_Count;$media_num++) { ?>
                    <a href="<?php echo $media[$media_num]; ?>" class="img" data-lightbox="group<?php echo $Tweet_num; ?>" style="background-image: url(<?php echo $media[$media_num] .':small'; ?>);"></a><?php } ?></li>
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

    <section class="search">
        <h1>Search<a name = "search"></a></h1>
        <form action="search.php" method="get">
            <input type="text" name="search_word" placeholder="キーワード検索">
            <input type="submit" value="検索">
        </form>
    </section>

</body>


<footer>
    <div id="title">
        <h1><a href="#search"><i class="fas fa-search"></i></a></h1>
    </div>
</footer>

</html>