<?php
  require_once('./common.php');
  $app->require_loggedin();
  $token = filter_input(INPUT_POST, TOKENNAME);
  if ($token !== $app->get('token')) {
    error_exit('正規の画面から使用ください');
  }
  $id = $app->get_id();
  $pwd   = filter_input(INPUT_POST, 'newpwd');
  $pwd2  = filter_input(INPUT_POST, 'newpwd2');
  $reqid = $app->requested_id(INPUT_POST);
  if ($pwd !== $pwd2) {
    error_exit('パスワードが一致していません');
  }
  if (mb_strlen($pwd) < 4) {
    error_exit("パスワードは4文字以上で指定してください");
  }
  try {
    $dbh = dblogin();
  
    $sql = 'UPDATE users SET pwd=? WHERE id=?';
    $sth = $dbh->prepare($sql);
    $rs = $sth->execute(array(mb_substr($pwd, 0, 6), $reqid));
  } catch (PDOException $e) {
    $app->addlog('クエリに失敗しました: ' . $e->getMessage());
    error_exit();
  }
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>パスワード変更</title>
</head>
<body>
<div id="top">
<?php require "menu.php"; ?>
  <div id="done">
    変更しました。<BR><BR>
  </div><!-- /#done -->
<?php require "footer.php"; ?>
</div>
</body>
</html>
