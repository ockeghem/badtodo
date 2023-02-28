<?php
  require_once('./common.php');
  $errmsg = array();

  $app->require_loggedin();
  $id = $app->get_id();
  $reqid = filter_input(INPUT_POST, 'id');
  if (empty($reqid))
    $reqid = $id;

  $icon  = $_FILES["icon"];
  $app->require_token();
  if ($icon['error'] !== 0) {
    $errmsg[] = 'アイコン画像を指定してください';
  } else if (exif_imagetype($icon["tmp_name"]) === false) {
    $errmsg[] = 'アイコン画像が不正です';
  } else {  
    $tmp_name = $icon["tmp_name"];
    $iconfname = uniqid() . '-' . $icon["name"];
    move_uploaded_file($tmp_name, "icons/$iconfname");
    try {
      $dbh = dblogin();

      $sql = "SELECT icon FROM users WHERE id=?";
      $sth = $dbh->prepare($sql);
      $sth->execute(array($reqid));
      $oldiconfname = $sth->fetchColumn();
      foreach (glob("icons/*$oldiconfname") as $file) {
        unlink($file);
      }
      
      $sql = 'UPDATE users SET icon=? WHERE id=?';
      $sth = $dbh->prepare($sql);
      $rs = $sth->execute(array($iconfname, $reqid));
    } catch (PDOException $e) {
      $app->addlog('クエリに失敗しました: ' . $e->getMessage());
      $errmsg[] = '只今サイトが大変混雑しています。もうしばらく経ってからアクセスしてください';
    }
  }
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>アイコン変更</title>
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
