<?php
require_once './common.php';
$id = $app->require_loggedin();
$errmsg = array();

function quit($app, $id, $reqid, $pwd) {
    try {
    $dbh = dblogin();
    $dbh->beginTransaction();

    $sql = 'SELECT pwd FROM users WHERE id=?';
    $sth = $dbh->prepare($sql);
    $rs = $sth->execute(array($id)); // 現在ログイン中のユーザのパスワードなので $id でよい
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    if ($pwd !== $row['pwd']) {
      return array('パスワードが違います');
    }

    $sql = "SELECT real_filename FROM todos WHERE owner=?";
    $sth = $dbh->prepare($sql);
    $sth->execute(array($reqid));
    foreach ($sth as $row) {
      @unlink("attachment/{$row['real_filename']}");
    }

    $sql = 'DELETE FROM todos WHERE owner=?';
    $sth = $dbh->prepare($sql);
    $rs = $sth->execute(array($reqid));

    $sql = "SELECT icon FROM users WHERE id=?";
    $sth = $dbh->prepare($sql);
    $sth->execute(array($reqid));
    $iconfilename = $sth->fetchColumn();
    foreach (glob("icons/*$iconfilename") as $file) {
      unlink($file);
    }

    $sql = 'DELETE FROM users WHERE id=?';
    $sth = $dbh->prepare($sql);
    $rs = $sth->execute(array($reqid));
    if ($reqid == $id) {
      $app->destroy();
    }
    $dbh->commit();
  } catch (PDOException $e) {
    $app->addlog('クエリに失敗しました: ' . $e->getMessage());
    if (isset($dbh)) {
      $dbh->rollBack();
    }
    return array('只今サイトが大変混雑しています。もうしばらく経ってからアクセスしてください');
  }
  return array();
}

$pwd   = mb_substr(filter_input(INPUT_POST, "pwd"), 0, 6);
$reqid = $app->requested_id(INPUT_POST);

$errmsg = quit($app, $id, $reqid, $pwd); 
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>退会しました</title>
</head>
<body>
<div id="top">
<?php require "menu.php"; ?>
  <div id="done">
    <?php if (empty($errmsg)): ?>
      退会しました<BR><BR>
    <?php else: 
      foreach ($errmsg as $msg) {
        echo "$msg<br>";
      }
      echo '<br><button type="button" onclick="window.history.back();">戻る</button>';
    endif; ?>
  </div><!-- /#done -->
<?php require "footer.php"; ?>
</div>
</body>
</html>
