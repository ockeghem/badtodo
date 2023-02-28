<?php
require_once './common.php';
$id = $app->require_loggedin();
$errmsg = array();

$ids = filter_input(INPUT_POST, 'id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
if (empty($ids)) {
  $errmsg[] = '項目をチェックして下さい';
} else {
  $process = filter_input(INPUT_POST, 'process');
  $result = '';

  try {
    $dbh = dblogin();

    foreach ($ids as $key => $value) {
      $keys[":id_$key"] = $value;
    }
    require("$process.php");
  } catch (PDOException $e) {
    $app->addlog('クエリに失敗しました: ' . $e->getMessage());
    $errmsg[] = '只今サイトが大変混雑しています。もうしばらく経ってからアクセスしてください';
  }
}
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>一括編集</title>
</head>
<body>
<div id="top">
<?php require "menu.php"; ?>
  <div id="done">
    <?php if (empty($errmsg)): 
      e($result . 'しました');
    else: 
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
