<?php
  require_once('./common.php');
  $app->require_loggedin();
  $id = $app->get_id();
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  if ($email === false) {
    error_exit('メールアドレスの形式が不正です', true);
  }
  $reqid = filter_input(INPUT_POST, 'id');
  if (empty($reqid))
    $reqid = $id;

  try {
    $dbh = dblogin();
    $dbh->beginTransaction();

    $sql = "SELECT COUNT(*) FROM users WHERE email=?";
    $sth = $dbh->prepare($sql);
    $sth->execute(array($email));
    $count = $sth->fetchColumn();
    if ($count > 0) {
      error_exit("メールアドレス($email)が重複しています", true);
    }
  
    //usleep(10000);

    $sql = 'UPDATE users SET email=? WHERE id=?';
    $sth = $dbh->prepare($sql);
    $rs = $sth->execute(array($email, $reqid));

    $dbh->commit();
  } catch (PDOException $e) {
    $app->addlog('クエリに失敗しました: ' . $e->getMessage());
    if (isset($dbh)) {
      $dbh->rollBack();
    }
    error_exit();
  }
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>メールアドレス変更</title>
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
