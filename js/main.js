$(document).ready(function () {
    $(".iframe").colorbox({ iframe: true, width: "80%", height: "80%" });
});
function toggle() {
    $('.toggle-box').slideToggle('slow');
}
function msgpopup() {
    alert("現在ツイート機能を利用できません。");
}

function init() {
    $(document).on('click', function (event) {
        if (!$(event.target).closest('.popup_TweetForm').length && !$(event.target).closest('.create_Tweet').length) {
            $('.popup_TweetForm').fadeOut();
        } else if ($(event.target).closest('.create_Tweet').length) {
            if ($('.popup_TweetForm').is(':hidden')) {
                $('.popup_TweetForm').fadeIn();
            } else {
                $('.popup_TweetForm').fadeOut();
            }
        }
    });
}

/**
 * （TODO）リツイートなのかふぁぼなのかを判別
 * それに応じ確認メッセージを変更する
 * ツイートの内容を引数で受けて表示する
*/
function check() {
    if (window.confirm('リツートします。よろしいですか？')) {
        return true;
    } else {
        return false;
    }
}