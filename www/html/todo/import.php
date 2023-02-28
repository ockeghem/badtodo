<?php
require_once './common.php';
$app->require_loggedin();
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>インポート</title>
</head>
<body>
<div id="top">
<?php $menu = 3; require "menu.php"; ?>
  <div id="newuser">
    TODOインポート<BR>
    <?php $app->form("importdo.php", true, array(), array("enctype" => "multipart/form-data")); ?>
    <table>
    <tr>
    <td>XMLファイル</td><td><input type="file" name="attachment"></td>
    </tr>
    <tr>
    <td></td><td><input type="submit" value="登録"></td>
    </tr>
    </table>
    </form>
  </div><!-- /#newuser -->
<?php require "footer.php"; ?>
</div>
</body>
</html>
