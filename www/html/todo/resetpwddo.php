<?php
  require_once('./common.php');
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  if ($email === false) {
    error_exit('メールアドレスの形式が不正です', true);
  }
  $app->require_token();

  try {
    $dbh = dblogin();
    $sql = "SELECT userid, pwd FROM users WHERE email='$email'";
    $sth = $dbh->query($sql);
    $result = $sth->fetch(PDO::FETCH_ASSOC);
    if (! empty($result)) {
      $userid  = $result['userid'];
      $pwd     = $result['pwd'];
      mb_send_mail($email, 
           'パスワードをお知らせします', 
           "$userid さん、あなたのパスワードは\n" . $pwd . " です\n");
    }
  } catch (PDOException $e) {
    $app->addlog('クエリに失敗しました: ' . $e->getMessage());
    error_exit();
  }
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>パスワードリセット</title>
</head>
<body>
<div id="top">
<?php require "menu.php"; ?>
  <div id="done">
    パスワードをご指定のメールアドレスに送信しました。<BR><BR>
    <?php $app->a('login.php', 'ログイン'); ?>
  </div><!-- /#done -->
<?php require "footer.php"; ?>
</div>
</body>
</html>
