<?php
  require_once('./common.php');
  $url = filter_input(INPUT_GET, 'url');
  if (empty($url)) {
    $url = "todolist.php";
  }
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>ログイン</title>
</head>
<body>
<div id="top">
<?php $menu = 8; require "menu.php"; ?>
  <div id="loginform">
    <?php $app->form("logindo.php", true); ?>
    ログインしてください
    <table>
      <tr>
        <td><input placeholder="ユーザID" type="text" name="userid" size="25"></td>
      </tr>
      <tr>
        <td><input placeholder="パスワード" type="text" name="pwd" size="25"></td>
      </tr>
      <tr>
        <td><input type="submit" value="ログイン"></td>
      </tr>
      <tr>
        <td><input type="checkbox" name="autologin" value="1" id="autologin"><label for="autologin">ログインしたままにする</label></td>
      </tr>
    </table>
    <input type=hidden name="url" value="<?php e($url); ?>">
    </form><BR>
    <?php $app->a('resetpwd.php', 'パスワードを忘れた方'); ?><br>
    初めての方は <?php $app->a('newuser.php', 'こちらから会員登録'); ?>してください<br>
  </div><!-- /#loginform -->
<?php require "footer.php"; ?>
</div>
</body>
</html>
