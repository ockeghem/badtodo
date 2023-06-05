<?php
require_once './common.php';
$app->require_loggedin();

function editdone($app, $todo, $c_date, $due_date, $done, $memo, $url, $url_text, $public, $item, $name, $realname) {
  try {
    $dbh = dblogin();

    if (empty($name)){
      $sql = 'UPDATE todos SET todo=?, c_date=?, due_date=?, done=?, memo=?, url=?, url_text=?, public=? WHERE id=?';
      $values = array($todo, $c_date, $due_date, $done, $memo, $url, $url_text, $public, $item);
    } else {
      $sql = "SELECT real_filename FROM todos WHERE real_filename IS NOT NULL AND id=?";
      $sth = $dbh->prepare($sql);
      $sth->execute(array($item));
      $oldrealname = $sth->fetchColumn();
      if ($oldrealname !== false ) {
        @unlink("attachment/$oldrealname");
      }
      $sql = 'UPDATE todos SET todo=?, c_date=?, due_date=?, done=?, memo=?, url=?, url_text=?, org_filename=?, real_filename=?, public=? WHERE id=?';
      $values = array($todo, $c_date, $due_date, $done, $memo, $url, $url_text, $name, $realname, $public, $item);
    }
    $sth = $dbh->prepare($sql);
    $sth->execute($values);
  } catch (PDOException $e) {
    $app->addlog('クエリに失敗しました: ' . $e->getMessage());
    return array('只今サイトが大変混雑しています。もうしばらく経ってからアクセスしてください');
  }
  return array();
}

$errmsg = array();

$item       = filter_input(INPUT_POST, "item");
$todo       = filter_input(INPUT_POST, "todo");
$c_date     = filter_input(INPUT_POST, "c_date");
$due_date   = filter_input(INPUT_POST, "due_date");
if ($due_date == "")
  $due_date = null;
$memo       = filter_input(INPUT_POST, "memo");
$url        = filter_input(INPUT_POST, "url");
$url_text   = filter_input(INPUT_POST, "url_text");
$done       = filter_input(INPUT_POST, "done") ? 1 : 0;
$public     = filter_input(INPUT_POST, "public") ? 1 : 0;
$token      = filter_input(INPUT_POST, TOKENNAME);
$attachment = @$_FILES["attachment"];

// CSRF対策
if ($app->get(TOKENNAME) != $token) {
  $errmsg[] = '正規の画面からアクセスしてください';
}

$id = $app->get_id();

$name = null;
$realname = null;
if (empty($todo)) {
  $errmsg[] = 'todoが空です';
}
if (! empty($url) && ! eregi("^[a-z]+:[-a-z0-9:/?=#!&%+~;.,*@()'[-_]*$", $url)) {
  $errmsg[] = 'URLが不正です';
}
if (empty($url) && ! empty($url_text)) {
  $errmsg[] = 'URLタイトルのみでURLがありません';
}
if ($attachment['error'] === 0) {
  $tmp_name = $attachment["tmp_name"];
  $name = $attachment["name"];
  $realname = dechex(time()) . '-' . $name;
  if (! safe_file($name)) {
    $errmsg[] = 'この拡張子のファイルはアップロードできません';
  }
  move_uploaded_file($tmp_name, "attachment/$realname");
}
if (empty($errmsg)) {
  $errmsg = editdone($app, $todo, $c_date, $due_date, $done, $memo, $url, $url_text, $public, $item, $name, $realname);
}

?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>Todo変更</title>
</head>
<body>
<div id="top">
<?php require "menu.php"; ?>
  <div id="done">

    <?php if (empty($errmsg)): ?>
      変更しました<br>
      <?php $app->a('todo.php', '戻る', true, array('item' => $item), array()); ?>  
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
