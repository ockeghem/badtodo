<?php
  require_once './common.php';
  $app->destroy();
  if (isset($_COOKIE['AUTOLOGIN'])) {
    setcookie('AUTOLOGIN', '', 0, '/');
  }
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>ログアウト</title>
</head>
<body>
<div id="top">
<?php $menu = 8; require "menu.php"; ?>
  <div id="done">
  ログアウトしました。<?php $app->a('login.php', '再度ログインする', true, array('url' => 'todolist.php')); ?><br>
  </div><!-- /#done -->
<?php require "footer.php"; ?>
</div>
</body>
</html>
