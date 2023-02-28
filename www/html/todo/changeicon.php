<?php
  require_once('./common.php');
  $app->require_loggedin();
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
<title>アイコン変更</title>
</head>
<body>
<div id="top">
<?php require "menu.php"; ?>
  <div id="newuser">
    アイコン変更(<?php e($requserid); ?>)<BR>
    <?php $app->form("changeicondo.php", true, array('id' => $reqid), array("enctype" => "multipart/form-data"), true); ?>
    <table>
    <tr>
    <td>アイコン画像</td><td><input name="icon" type="file"></td>
    </tr>
    <tr>
    <td></td><td><input type=submit value="変更"></td>
    </tr>
    </table>
    </form>
  </div><!-- /#newuser -->
<?php require "footer.php"; ?>
</div>
</body>
</html>
