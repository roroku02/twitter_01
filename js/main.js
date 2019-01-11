$(document).ready(function () {
    $(".iframe").colorbox({ iframe: true, width: "80%", height: "80%" });
});
function toggle() {
    $('.toggle-box').slideToggle('slow');
}
function msgpopup() {
    alert("現在ツイート機能を利用できません。");
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