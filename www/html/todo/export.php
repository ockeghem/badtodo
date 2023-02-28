<?php
require_once './common.php';
$app->require_loggedin();
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>エクスポート</title>
</head>
<body>
<div id="top">
<?php $menu = 4; require "menu.php"; ?>
  <div id="contents">
    todoをエクスポートします。「開始」ボタンを押してください<br>
    <?php $app->form("exportdo.php"); ?>
      <input type="submit" value="開始">
    </form>
  </div><!-- /#contents -->
<?php require "footer.php"; ?>
</div>
</body>
</html>
