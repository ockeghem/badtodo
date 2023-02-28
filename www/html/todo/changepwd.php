<?php
  require_once('./common.php');
  $app->require_loggedin();
  $id = $app->get_id();
  $reqid = $app->requested_id();
  $token = md5($id);
  $app->set('token', $token);
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
<title>パスワード変更</title>
</head>
<body>
<div id="top">
<?php require "menu.php"; ?>
  <div id="changepwd">
    パスワード変更(<?php e($requserid); ?>)<BR>
    <?php $app->form("changepwddo.php", true, array('id' => $reqid, TOKENNAME => $token), array()); ?>
    <table>
    <tr>
    <td>パスワード</td><td><input name="newpwd" type="password" size="16"></td>
    </tr>
    <tr>
    <td>パスワード（再）</td><td><input name="newpwd2" type="password" size="16"></td>
    </tr>
    <tr>
    <td></td><td><input type=submit value="変更"></td>
    </tr>
    </table>
    </form>
  </div><!-- /#changepwd -->
<?php require "footer.php"; ?>
</div>
</body>
</html>
