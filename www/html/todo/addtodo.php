<?php
require_once './common.php';
$app->require_loggedin();
$errmsg = array();

function add_todo($app, $todo, $due_date, $memo, $org_filename, $real_filename, $url, $url_text, $public) {
  try {
    $dbh = dblogin();
    $dbh->beginTransaction();
  
    $sql = "SELECT MAX(id) FROM todos";
    $sth = $dbh->query($sql);
    $maxid = $sth->fetchColumn();
    
    $sql = 'INSERT INTO todos (id, owner, todo, due_date, memo, org_filename, real_filename, url, url_text, public)  VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
    $sth = $dbh->prepare($sql);
    $rs = $sth->execute(array($maxid + 1, $app->get_id(), $todo, $due_date, $memo, $org_filename, $real_filename, $url, $url_text, $public));
  
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

$todo       = filter_input(INPUT_POST, "todo");
$due_date   = filter_input(INPUT_POST, "due_date");
if (empty($due_date))
  $due_date = null;
$memo       = filter_input(INPUT_POST, "memo");
$public     = filter_input(INPUT_POST, "public") ? 1 : 0;
$url        = filter_input(INPUT_POST, "url");
$url_text   = filter_input(INPUT_POST, "url_text");
$attachment = $_FILES["attachment"];

$app->require_token();
if (empty($todo)) {
  $errmsg[] = 'todoが空です';
}
if (! empty($url) && ! eregi("^[a-z]+:[-a-z0-9:/?=#!&%+~;.,*@()'[-_]*$", $url)) {
  $errmsg[] = 'URLが不正です';
}
if (empty($url) && ! empty($url_text)) {
  $errmsg[] = 'URLタイトルのみでURLがありません';
}
$org_filename = null;
$real_filename = null;
if ($attachment['error'] === 0) {
  $tmp_name = $attachment["tmp_name"];
  $org_filename = $attachment["name"];
  $real_filename = dechex(time()) . "-" . $org_filename;
  move_uploaded_file($tmp_name, "attachment/$real_filename");
}
if (empty($errmsg)) {
  $errmsg = add_todo($app, $todo, $due_date, $memo, $org_filename, $real_filename, $url, $url_text, $public);
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>Todo追加</title>
</head>
<body>
<div id="top">
<?php require "menu.php"; ?>
  <div id="done">
    <?php if (empty($errmsg)): ?>
      1件追加しました<BR><BR>
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
