<?php
session_start();
require_once('./twitteroauth/autoload.php');

use Abraham\TwitterOAuth\TwitterOAuth;

$ConsumerKey = "m8fmmQGoGbRpooxPGwgGg";
$ConsumerSecret = "Llbr5TBIL0VcxZNS4jcIGXOq3qelCADnthYfjUeUQs";
$AccessToken = $_SESSION['access_token'];

$connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret,$AccessToken['oauth_token'],$AccessToken['oauth_token_secret']);

$tweet = "";

$year = $_GET['year'];
$season = $_GET['season'];

$anime_tag_url = "http://api.moemoe.tokyo/anime/v1/master/$year/$season";
//echo $anime_tag_url;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $anime_tag_url); // 取得するURLを指定
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 実行結果を文字列で返す
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // サーバー証明書の検証を行わない
$response = curl_exec($ch);
$response = json_decode($response, true);
//print_r($response);
curl_close($ch); 
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
</script>
<header>
    <div id="title">
        <h1><i class="fab fa-twitter"></i></h1>
    </div>
</header>

<body>
<table border="1">
    <tr>
        <th>title</th>
        <th>tag</th>
    </tr>
    <?php
    for($i = 0;$i < count($response);$i++){
        $anime_title[] = $response[$i]['title'];
        if(isset($response[$i]['twitter_hash_tag'])){
            $anime_tag[] = $response[$i]['twitter_hash_tag'];
        }else $anime_tag[$i] = NULL;
        echo '<tr><td>' .$anime_title[$i]. '</th><td>#' .$anime_tag[$i]. '</td></tr>';
    }
    ?>
</table>

<section class="option">
    <h1>タイトルをクリックすると検索します</h1>
    <form action="search.php" method="get" id="search_button">
        <?php for($i = 0;$i < count($anime_title);$i++){?>
            <button type="submit" name="search_word" value="#<?php echo $anime_tag[$i]; ?>"><?php echo $anime_title[$i]; ?></button>
        <?php } ?>
    </form>
</section>
</body>

</html>