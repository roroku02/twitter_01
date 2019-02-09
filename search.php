<?php
    session_start();
    require_once('./twitteroauth/autoload.php');
    
    use Abraham\TwitterOAuth\TwitterOAuth;

    $ConsumerKey = "m8fmmQGoGbRpooxPGwgGg";
    $ConsumerSecret = "Llbr5TBIL0VcxZNS4jcIGXOq3qelCADnthYfjUeUQs";
    $AccessToken = $_SESSION['access_token'];
    
    $connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret,$AccessToken['oauth_token'],$AccessToken['oauth_token_secret']);

    //初期化
    $RT_sort = FALSE;
    $Fav_sort = FALSE;
    $only_verify = FALSE;
    
    //ツイート並び替えボタンの取得
    if(isset($_GET['option'])){
        if($_GET['option'] == "popular"){
            $tweet_sort = $_GET['option'];
        }elseif($_GET['option'] == "rt"){
            $RT_sort = TRUE;
            $tweet_sort = "recent";
        }elseif($_GET['option'] == 'fav'){
            $Fav_sort = TRUE;
            $tweet_sort = "recent";
        }else{
            $tweet_sort = "recent";
        }
    }else{
        $tweet_sort = "recent";
        $_GET['search_word'] = htmlspecialchars($_GET['search_word'],ENT_QUOTES,'UTF-8');       //検索文字列エスケープ処理
        $_SESSION['search_word'] = $_GET['search_word'];
    }

    $max_id = NULL;     //初期化
    $now_time = time(); //現在時刻取得
    $params = array(    //検索パラメータ
        'q' => $_SESSION['search_word'],
        'exclude' => 'retweets',
        'count' => 100,
        'tweet_mode' => 'extended',
        'result_type' => $tweet_sort
    );

    //順次出力処理
    ob_implicit_flush(true);
    while(@ob_end_clean());

    if($RT_sort == TRUE){       //RTソート
        echo "<div id = 'loading' style='position:fixed;top:50%;left:50%;'>";
        echo "<img src= './images/loading.svg'>";
        echo "<div id = 'percent'>";
        for($i = 0;$i < 10; $i++){
            echo "<script>document.getElementById( 'percent' ).innerHTML = ''</script>";
            echo ($i + 1) * 10 . "%完了<br/>";   
            ${'search_tweets' . $i} = $connection -> get('search/tweets',$params);
            unset(${'search_tweets' . $i} -> {'search_metadata'});
            ${'search_tweet' . $i} = ${'search_tweets' . $i} -> {'statuses'};
            $max_id = end(${'search_tweets' .$i} -> {'statuses'}) -> {'id_str'};
            echo "max_id = $max_id";
            echo '<br />';
            //print_r(${'search_tweets' . $i});
            if(isset($max_id)){
                if(PHP_INT_SIZE == 4)
                    $params['max_id'] = $max_id;
                elseif(PHP_INT_SIZE == 8)
                    $params['max_id'] = $max_id - 1;
            }
            //echo "$i : " . sizeof(${'search_tweet' . $i}) . "<br>";
        }
        echo "</div>";
        $search_tweet = array_merge_recursive($search_tweet0,$search_tweet1,$search_tweet2,$search_tweet3,$search_tweet4,$search_tweet5,$search_tweet6,$search_tweet7,$search_tweet8,$search_tweet9);
        echo "</div>";
        $count_t = sizeof($search_tweet);
        echo "$count_t 件ツイート取得";
        foreach($search_tweet as $key => $value){
            $sort[$key] = $value -> {'retweet_count'};
        }
        array_multisort($sort,SORT_DESC,$search_tweet);
    }elseif($Fav_sort == TRUE){     //favoriteソート    //修正必要あり
        if(isset($search_tweet0)){
            foreach($search_tweet as $key => $value){
                $sort[$key] = $value['favorite_count'];
            }
        array_multisort($sort,SORT_DESC,$seach_tweet);
        }else{
        //ロード割合表示
        echo "<div id = 'loading' style='position:fixed;top:50%;left:50%;'>";
        echo "<img src= './images/loading.svg'>";
        echo "<div id = 'percent'>";
        for($i = 0;$i < 10; $i++){
            echo "<script>document.getElementById( 'percent' ).innerHTML = ''</script>";
            echo ($i + 1) * 10 . "%完了<br/>";        
            //print_r($params);
            ${'search_tweets' . $i} = $connection -> get('search/tweets',$params);
            unset(${'search_tweets' . $i} -> {'search_metadata'});
            ${'search_tweet' . $i} = ${'search_tweets' . $i} -> {'statuses'};
            $max_id = end(${'search_tweets' .$i} -> {'statuses'})->{'id_str'};
            if(isset($max_id)){     //ページング処理
                if(PHP_INT_SIZE == 4)
                    $params['max_id'] = $max_id;
                elseif(PHP_INT_SIZE == 8)
                    $params['max_id'] = $max_id - 1;
            }
            //echo "$i : " . sizeof(${'search_tweet' . $i}) . "<br>";
        }
        echo "</div>";
        
        //連想配列の結合
        $search_tweet = array_merge_recursive($search_tweet0,$search_tweet1,$search_tweet2,$search_tweet3,$search_tweet4,$search_tweet5,$search_tweet6,$search_tweet7,$search_tweet8,$search_tweet9);
        echo "</div>";
        
        foreach($search_tweet as $key => $value){
            $sort[$key] = $value -> {'favorite_count'};
        }
        array_multisort($sort,SORT_DESC,$search_tweet);     //ソート処理
        sizeof($search_tweet);
        }
    }else{
        $search_tweet = $connection -> get('search/tweets',$params);
        unset($search_tweet -> {'search_metadata'});
        $temp = $search_tweet -> {'statuses'};
        $search_tweet = $temp;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php echo $_SESSION['search_word']; ?>の検索結果 | Twitterクライアント</title>
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
   <!-- ヘッダー/Twitterアイコン -->
    <div id="title">
        <i class="fab fa-twitter"></i>
    </div>
    <!-- ヘッダー/ログアウトボタン -->
    <div id="logout_button">
        <a href="./logout.php"><i class="fas fa-sign-out-alt"></i>ログアウト</a>
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
   <form action="search.php" method="get" class="sort">
       <input type="radio" name="option" value="recent" id="select1" onchange="this.form.submit()" <?php if($tweet_sort == "recent" && $RT_sort == FALSE && $Fav_sort == FALSE) echo "checked"; ?>>
       <label for="select1">新しい順</label>
       <input type="radio" name="option" value="rt" id="select3" onclick="load()" onchange="this.form.submit()" <?php if($tweet_sort == "recent" && $RT_sort == TRUE) echo "checked"; ?>>
       <label for="select3">RT順</label> 
       <input type="radio" name="option" value="fav" id="select4" onclick="load()" onchange="this.form.submit()" <?php if($tweet_sort == "recent" && $Fav_sort == TRUE) echo "checked"; ?>>
       <label for="select4">いいね順</label>
       <input type="radio" name="option" value="popular" id="select2" onchange="this.form.submit()" <?php if($only_verify == TRUE) echo "checked"; ?>>
       <label for="select2">認証済みユーザのみ（新しい順）</label>
    </form>
    <?php
    if(sizeof($search_tweet) < 100){
        $count_max = sizeof($search_tweet);
    }else{
        $count_max = 100;
    }
    for($Tweet_num = 0; $Tweet_num < $count_max; $Tweet_num++){
        $TweetID = $search_tweet[$Tweet_num]->{"id"};
        $Date = $search_tweet[$Tweet_num]->{"created_at"};
        $Tweet_time = strtotime($Date);
        $relative_time = $now_time - $Tweet_time;
        $Text = $search_tweet[$Tweet_num]->{"full_text"};
        $User_ID = $search_tweet[$Tweet_num]->{"user"}->{"screen_name"};
        $User_Name = $search_tweet[$Tweet_num]->{"user"}->{"name"};
        $Profile_image_URL = $search_tweet[$Tweet_num]->{"user"}->{"profile_image_url_https"};
        $Retweet_Count = $search_tweet[$Tweet_num]->{"retweet_count"};
        $Favorite_Count = $search_tweet[$Tweet_num]->{"favorite_count"};
        $Retweet_TRUE = FALSE;
        $media_URL = NULL;

        //RT処理
        if(isset($search_tweet[$Tweet_num]->{"retweeted_status"})){
            $Retweet_TRUE = TRUE;
            $Date = $search_tweet[$Tweet_num]->{"retweeted_status"}->{"created_at"};
            $RT_User = $User_Name;
            $Text = $search_tweet[$Tweet_num]->{"retweeted_status"}->{"full_text"};
            $User_ID = $search_tweet[$Tweet_num]->{"retweeted_status"}->{"user"}->{"screen_name"};
            $User_Name = $search_tweet[$Tweet_num]->{"retweeted_status"}->{"user"}->{"name"};
            $Profile_image_URL = $search_tweet[$Tweet_num]->{"retweeted_status"}->{"user"}->{"profile_image_url_https"};
            $Retweet_Count = $search_tweet[$Tweet_num]->{"retweeted_status"}->{"retweet_count"};
            $Favorite_Count = $search_tweet[$Tweet_num]->{"retweeted_status"}->{"favorite_count"};    
            if(isset($search_tweet[$Tweet_num]->{"retweeted_status"}->{"entities"}->{"hashtags"}));
                $search_tweet[$Tweet_num]->{"entities"}->{"hashtags"} = $search_tweet[$Tweet_num]->{"retweeted_status"}->{"entities"}->{"hashtags"};
            if(isset($search_tweet[$Tweet_num]->{"retweeted_status"}->{"extended_entities"}->{"media"})){
                foreach($search_tweet[$Tweet_num]->{"retweeted_status"}->{"extended_entities"}->{"media"} as $media){
                    $media_URL[] = $media->media_url_https;
                }
            }
        }

            //ハッシュタグ処理
            $search_tweet[$Tweet_num]->{"entities"}->{"hashtags"} = array_reverse($search_tweet[$Tweet_num]->{"entities"}->{"hashtags"});
            foreach($search_tweet[$Tweet_num]->{"entities"}->{"hashtags"} as $hashtags){
                if(isset($hashtags)){
                    $hashtag_text = $hashtags->text;
                    $hashtag_indices = $hashtags->indices;
                    $left_text = mb_substr($Text,0,$hashtag_indices[0]);
                    $right_text = mb_substr($Text,($hashtag_indices[0] + ($hashtag_indices[1] - $hashtag_indices[0])));
                    $after_text = '<a href="http://localhost/twitter_01/search.php?search_word=' . rawurlencode("#" . $hashtag_text) . '">#' . $hashtag_text . '</a>';
                    $Text = $left_text . $after_text . $right_text;
                }
            }
            if(isset($search_tweet[$Tweet_num]->{"extended_entities"}->{"media"})){
                foreach($search_tweet[$Tweet_num]->{"extended_entities"}->{"media"} as $media){
                    $media_URL[] = $media->media_url_https;
                }
            }
            if(isset($search_tweet[$Tweet_num]->{"entities"}->{"urls"})){
                foreach($search_tweet[$Tweet_num]->{"entities"}->{"urls"} as $urls){
                    $Text = str_replace($urls->url,'<a href="'.$urls->expanded_url.'" class= "iframe">'.$urls->display_url.'</a>',$Text);
                }
            }

            //承認済みユーザの取得
            $Verified_User = FALSE;
            if($search_tweet[$Tweet_num]->{"user"}->{"verified"} == "1"){
                $Verified_User = TRUE;
            }elseif($User_ID == "roroku02"){
                $Verified_User = TRUE;
            }
            
        ?>
            <ul>
            <?php if($Retweet_TRUE == TRUE){ ?>
                <p class="retweet_sentence"><i class="fas fa-retweet fa-fw"></i><?php echo $RT_User; ?>がリツイート</p> <?php } ?>
                <div id = "Tweet_header">
                    <div id = "User_info">
                        <li><img src =<?php echo $Profile_image_URL; ?>></li>
                        <div id="User_NameID">
                            <li id = "User_Name"><?php echo $User_Name ?><?php if($Verified_User == TRUE){ ?>
                                <img src="./images/verified_account.png" />
                            <?php } ?></li>
                            
                            <li id = "User_ID">@<?php echo $User_ID ?></li>
                        </div>
                    </div>
                <!-- 相対時間表示 -->
                <li><?php if($relative_time < 60){ 
                    echo $relative_time . "秒前";
                }elseif($relative_time >= 60 && $relative_time < (60 * 60)){
                    echo floor($relative_time / 60) . "分前";
                }elseif($relative_time >= (60 * 60) && $relative_time < (60 * 60 * 24)){
                    echo floor($relative_time / (60 * 60)) . "時間前";
                }elseif($relative_time >= (60 * 60 * 24)){
                    echo date("Y/n/j G:i",$Tweet_time);
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
        <a href="main.php"><i class="fas fa-home"></i></a>
    </div>
</footer>
</html>