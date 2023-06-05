<?php
require_once './common.php';
$id = $app->require_loggedin();

$item  = filter_input(INPUT_POST, "item");
$token = filter_input(INPUT_POST, TOKENNAME);

// CSRF対策

try {
  $dbh = dblogin();
  $sql = "SELECT real_filename FROM todos INNER JOIN users ON todos.id = ? AND users.id = todos.owner AND (todos.owner = ? OR ? > 0)";
  $sth = $dbh->prepare($sql);
  $sth->execute(array($item, $id, $app->is_super()));
  $file = $sth->fetchColumn();
  if (empty($file)) {
    error_exit('権限がないかファイルがありません', true);
  }
  @unlink("attachment/$file");

  $sql = 'UPDATE todos SET org_filename=NULL, real_filename=NULL WHERE id=?';
  $sth = $dbh->prepare($sql);
  $rs = $sth->execute(array($item));
} catch (PDOException $e) {
  $app->addlog('クエリに失敗しました: ' . $e->getMessage());
  error_exit();
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
    添付ファイルを削除しました<br>
    <?php $app->a('todo.php', '戻る', true, array('item' => $item), array()); ?>  
  </div><!-- /#done -->
<?php require "footer.php"; ?>
</div>
</body>
</html>
