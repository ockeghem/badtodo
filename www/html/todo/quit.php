<?php
  require_once('./common.php');
  $id = $app->require_loggedin();
  $reqid = $app->requested_id();
  $token = $app->get_token();
  try {
    $dbh = dblogin();
    $sql = "SELECT userid FROM users WHERE id=?";
    $sth = $dbh->prepare($sql);
    $sth->execute(array($reqid));
    $requserid = $sth->fetchColumn();
  } catch (PDOException $e) {
    $app->addlog('クエリに失敗しました: ' . $e->getMessage());
    error_exit();
  }
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>退会処理</title>
</head>
<body>
<div id="top">
<?php require "menu.php"; ?>
  <div id="changepwd">
    本当に退会しますか?(<?php e($requserid); ?>)<BR>
    <?php $app->form("quitdo.php", true, array('id' => $reqid), array(), true); ?>
    <table>
    <tr>
    <td>パスワード</td><td><input name="pwd" type="password" size="16"></td>
    </tr>
    <tr>
    <td></td><td><input type=submit value="退会"></td>
    </tr>
    </table>
    </form>
  </div><!-- /#changepwd -->
<?php require "footer.php"; ?>
</div>
</body>
</html>
