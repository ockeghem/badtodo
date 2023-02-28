<?php
  require_once('./common.php');
  $id = $app->require_loggedin();
  $app->require_token();

  function update_user($app, $reqid, $userid, $email, $pwd, $iconfname, $super) {
    try {
      $dbh = dblogin();
      $dbh->beginTransaction();
      $columns = array();
      $values   = array();
      $errmsg = array();      
      $oldiconfname = '';

      $sql = "SELECT userid, email, icon, super FROM users WHERE id=?";
      $sth = $dbh->prepare($sql);
      $sth->execute(array($reqid));
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      if (! empty($userid) && $userid !== $row['userid']) {
        $sql = "SELECT COUNT(*) FROM users WHERE userid=?";
        $sth = $dbh->prepare($sql);
        $sth->execute(array($userid));
        $count = $sth->fetchColumn();
        if ($count == 0) {
          $columns[] = 'userid=?';
          $values[] = $userid;
        } else {
          $errmsg[] = "ユーザ名($userid)が重複しています";
        }
      }
      if (! empty($email) && $email !== $row['email']) {
        $sql = "SELECT COUNT(*) FROM users WHERE email=?";
        $sth = $dbh->prepare($sql);
        $sth->execute(array($email));
        $count = $sth->fetchColumn();
        if ($count == 0) {
          $columns[] = 'email=?';
          $values[] = $email;
        } else {
          $errmsg[] = 'メールアドレスが重複しています';
        }
      }
      if (! empty($pwd)) {
        $columns[] = 'pwd=?';
        $values[] = mb_substr($pwd, 0, 6);
      }
      if ($super != $row['super']) {
        $columns[] = 'super=?';
        $values[] = $super;
      }
      if (! empty($iconfname)) {
        $oldiconfname = $row['icon'];
        $columns[] = 'icon=?';
        $values[] = $iconfname;
      }
      if (! empty($errmsg))
        return $errmsg;
      // 更新すべきカラムがあればSQL文を組み立ててUPDATE文を実行する
      if (! empty($columns)) {
        $sql = "UPDATE users SET " . implode(',', $columns) . " WHERE id=?";
        $values[] = $reqid;
        $sth = $dbh->prepare($sql);
        $rs = $sth->execute($values);
      } else {
        $errmsg[] = '変更がありません';
      }
      $dbh->commit();
      // SQLクエリが正常終了してからファイルを削除する
      if (! empty($oldiconfname)) {
        foreach (glob("icons/*$oldiconfname") as $file) {
          unlink($file);
        }
      }
    } catch (PDOException $e) {
      $app->addlog('クエリに失敗しました: ' . $e->getMessage());
      if (isset($dbh)) {
        $dbh->rollBack();
      }
      $errmsg[] = array('只今サイトが大変混雑しています。もうしばらく経ってからアクセスしてください');
    }
    return $errmsg;
  } // function update_user()

  $errmsg = array();

  $reqid = $app->requested_id(INPUT_POST);
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  if ($email === false) {
    $errmsg[] = 'メールアドレスの形式が不正です';
  }
  $userid = filter_input(INPUT_POST, "userid");
  $pwd   = filter_input(INPUT_POST, 'newpwd');
  $pwd2  = filter_input(INPUT_POST, 'newpwd2');
  if ($pwd !== $pwd2) {
    $errmsg[] = 'パスワードが一致していません';
  }
  if (! empty($pwd) && mb_strlen($pwd) < 4) {
    $errmsg[] = "パスワードは4文字以上で指定してください";
  }
  $super = filter_input(INPUT_POST, 'super', FILTER_VALIDATE_BOOLEAN);
  if (empty($super))
    $super = 0;

  $reqid = filter_input(INPUT_POST, 'id');
  if (empty($reqid))
    $reqid = $id;
  $icon  = $_FILES["icon"];
  if ($icon['error'] === 0) {
    $tmp_name = $icon["tmp_name"];
    $iconfname = uniqid() . '-' . $icon["name"];
    move_uploaded_file($tmp_name, "icons/$iconfname");
  } else {
    $tmp_name = null;
    $iconfname = null;
  }
  if (empty($errmsg)) {
    $errmsg = update_user($app, $reqid, $userid, $email, $pwd, $iconfname, $super);
  }
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>メールアドレス変更</title>
</head>
<body>
<div id="top">
<?php require "menu.php"; ?>
  <div id="done">
    <?php if (empty($errmsg)): ?>
      変更しました。<BR><BR>
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
