<?php
$nosession = true;
require_once '../../common.php';
$userid = filter_input(INPUT_GET, "id");
$errmsg = array();
try {
  if (! ereg('^[a-zA-Z0-9]{3,16}$', $userid)) {
    echo '{"ok": false, "message": "ユーザIDは英数字で3文字以上、16文字以内で指定してください"}';
    exit;
  }
  $dbh = dblogin();
  $sql = "SELECT COUNT(*) FROM users WHERE userid='$userid'";
  $sth = $dbh->query($sql);
  $duplicate = $sth->fetchColumn() > 0;
  if ($duplicate) {
    echo json_encode(array('ok' => false, 'message' => 'このIDは既に登録されています'));
  } else {
    echo '{"ok": true}';
  }
} catch (PDOException $e) {
  echo json_encode(array('ok' => false, 'message' => $e->getMessage()));
}
