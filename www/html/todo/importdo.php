<?php
require_once './common.php';
$id = $app->require_loggedin();
$errmsg = array();

function import_todo($app, $ownerid, $todolist) {  
  try {
    $dbh = dblogin();
    $dbh->beginTransaction();

    $sql = "SELECT MAX(id) FROM todos";
    $sth = $dbh->query($sql);
    $maxid = $sth->fetchColumn();

    $keylist = array('subject', 'memo', 'url', 'url_text', 'c_date', 'due_date', 'done', 'public');
    foreach ($todolist as $todo) {
      foreach ($keylist as $key) {
        $elements = $todo->getElementsByTagName($key);
        if ($elements->length > 0) {
          $$key = $elements->item(0)->textContent;
        } else {
          $$key = null;
        }
      }
      $c_date = empty($c_date) ? 'NULL' : "'{$c_date}'";
      $due_date = empty($due_date) ? 'NULL' : "'{$due_date}'";
      $maxid++;
      $sql = "INSERT INTO todos (id, owner, todo, c_date, due_date, done, memo, url, url_text, public) VALUES($maxid, $ownerid, '$subject', $c_date, $due_date, $done, '$memo', '$url', '$url_text', $public)";
      $dbh->query($sql);    
    }
    $dbh->commit();
  } catch (PDOException $e) {
    if (isset($dbh)) {
      $dbh->rollBack();
    }
    $app->addlog('クエリに失敗しました: ' . $e->getMessage());
    return array('只今サイトが大変混雑しています。もうしばらく経ってからアクセスしてください');
  }
}

$xmlfile = isset($_FILES["attachment"]) ? $_FILES["attachment"] : array('error' => 1);
if ($xmlfile['error'] !== 0) {
  $errmsg[] = 'XMLフアイルがありません';
} else {
  $tmp_name = $xmlfile["tmp_name"];
  $doc = new DOMDocument();
  $doc->load($tmp_name);
  $todolist = $doc->getElementsByTagName('todo');
}
if (empty($errmsg)) {
  $errmsg = import_todo($app, $id, $todolist);
}
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>インポート</title>
</head>
<body>
<div id="top">
<?php $menu = 3; require "menu.php"; ?>
  <div id="done">
    <?php if (empty($errmsg)): 
       e($todolist->length); ?> 件登録しました
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
