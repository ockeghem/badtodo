<?php
  require_once('./common.php');
  $token = $app->get_token();
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>パスワードリセット</title>
</head>
<body>
<div id="top">
<?php require "menu.php"; ?>
  <div id="pwreset">
    パスワードを忘れた方は登録済みメールアドレスを入力してください<BR>
    <?php $app->form("resetpwddo.php", true, array(), array(), true); ?>
    <table>
    <tr>
    <td>Eメール</td><td><input name="email" size="32"></td>
    </tr>
    <tr>
    <td></td><td><input type=submit value="送信"></td>
    </tr>
    </table>
    </form>
  </div><!-- /#pwreset -->
<?php require "footer.php"; ?>
</div>
</body>
</html>
