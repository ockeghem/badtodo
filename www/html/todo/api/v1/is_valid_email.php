<?php
$nosession = true;
require_once '../../common.php';
$email = filter_input(INPUT_GET, "email");
$errmsg = array();
try {
  if (! validEmailAddress($email)) {
    echo '{"ok": false, "message": "メールアドレス形式が不正です"}';
    exit;
  }
  $dbh = dblogin();
  $sql = "SELECT COUNT(*) FROM users WHERE email='$email'";
  $sth = $dbh->query($sql);
  $duplicate = $sth->fetchColumn() > 0;
  if ($duplicate) {
    echo json_encode(array('ok' => false, 'message' => 'そのメールアドレスは既に登録されています'));
  } else {
    echo '{"ok": true}';
  }
} catch (PDOException $e) {
  echo json_encode(array('ok' => false, 'message' => $e->getMessage()));
}
