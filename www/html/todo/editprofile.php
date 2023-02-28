<?php
  require_once('./common.php');
  $id = $app->require_loggedin();
  $reqid = filter_input(INPUT_GET, 'id');
  if (empty($reqid))
    $reqid = $id;
  $token = $app->get_token();
  $ok = $app->is_super() || $id === $reqid;
  if ($ok) {
    try {
      $dbh = dblogin();
      $sql = "SELECT id, userid, pwd, email, icon, super FROM users WHERE id=?";
      $sth = $dbh->prepare($sql);
      $sth->execute(array($reqid));
      $result = $sth->fetch(PDO::FETCH_ASSOC);
      if (empty($result)) {
        $ok = false;
      } else {
        $userid = $result['userid']; 
        $email = $result['email'];
        $pwd   = $result['pwd'];
        $icon  = $result['icon'];
        $super = $result['super'];
      }
    } catch (PDOException $e) {
      $app->addlog('クエリに失敗しました: ' . $e->getMessage());
      error_exit();
    }
  }
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>マイページ</title>
</head>
<body>
<div id="top">
<?php $menu = 5; require "menu.php"; ?>
  <div id="contents">
  <?php if ($ok) : 
    $app->form("editprofiledo.php", true, array('id' => $reqid), array("enctype" => "multipart/form-data"), true);  ?>
        <table style="width: 70%;">
          <tr>
          <td>ID</td><td><input name="userid" value="<?php echo $userid; ?>"></td>
          </tr>
          <tr>
          <td>メールアドレス</td><td><input name="email" value="<?php echo $email; ?>"></td>
          </tr>
          <tr>
          <td>パスワード</td><td><input name="newpwd" type="password"></td>
          </tr>
          <tr>
          <td>パスワード（再）</td><td><input name="newpwd2" type="password"></td>
          </tr>
          <tr>
          <td>アイコン</td><td><input name="icon" type="file"></td>
          </tr>
          <tr>
          <td>管理者権限</td><td><input type="checkbox" name="super" value="1"
            <?php if (! $app->is_super()) echo " disabled"; ?>
            <?php if ($super) echo " checked"; ?>>
          </td>
          </tr>
        </table>
        <input type="submit" value="変更">
    </form>
  <?php else : ?>
    権限がないか、そのユーザは存在しません
  <?php endif; ?>
  </div><!-- /#contents -->
<?php require "footer.php"; ?>
</div>
</body>
</html>
