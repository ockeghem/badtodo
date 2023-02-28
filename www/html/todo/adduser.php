<?php
require_once './common.php';

function adduser($app, $userid, $pwd, $email, $icon, $super) {
  $errmsg = array();
  try {
    $dbh = dblogin();
    $dbh->beginTransaction();

    $sql = "SELECT COUNT(*) FROM users WHERE userid=?";
    $sth = $dbh->prepare($sql);
    $sth->execute(array($userid));
    $count = $sth->fetchColumn();
    if ($count > 0) {
      $errmsg[] = 'ユーザIDが重複しています';
    }
    $sql = "SELECT COUNT(*) FROM users WHERE email=?";
    $sth = $dbh->prepare($sql);
    $sth->execute(array($email));
    $count = $sth->fetchColumn();
    if ($count > 0) {
      $errmsg[] = 'メールアドレスが重複しています';
    }
    if (! empty($errmsg)) {
      $dbh->rollBack();
      return $errmsg;
    }

    rename("temp/$icon", "icons/$icon");
    @unlink("temp/_64_$icon");   // 縮小画像を削除しておく 2023/1/5
    $sql = "SELECT MAX(id) FROM users";
    $sth = $dbh->query($sql);
    $maxid = $sth->fetchColumn();
    
    $sql = 'INSERT INTO users VALUES(?, ?, ?, ?, ?, ?)';
    $sth = $dbh->prepare($sql);
    $rs = $sth->execute(array($maxid + 1, $userid, $pwd, $email, $icon, $super));
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

$errmsg = array();
$userid = filter_input(INPUT_POST, "id");
$pwd   = mb_substr(filter_input(INPUT_POST, "pwd"), 0, 6);
$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
$super = filter_input(INPUT_POST, 'super', FILTER_VALIDATE_BOOLEAN);
if (empty($super))
  $super = 0;
if ($email === false) {
  $errmsg[] = 'メールアドレスの形式が不正です';
}
$icon  = filter_input(INPUT_POST, "iconfname");
if (empty($icon)) {
  $errmsg[] = 'アイコンファイルを指定してください';
}

if (empty($errmsg)) {
  $errmsg = adduser($app, $userid, $pwd, $email, $icon, $super);
}
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>会員登録</title>
</head>
<body>
<div id="top">
<?php require "menu.php"; ?>
  <div id="done">
    <?php if (empty($errmsg)): ?>
      登録しました。<BR><BR>
      <?php if(! $app->is_super()): ?>
        続いて <?php $app->a('login.php', 'ログイン'); ?>してください。<br>
      <?php endif; ?>
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
