<?php
require_once './common.php';
$token = $app->get_token();
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>問い合わせ</title>
</head>
<body>
<div id="top">
<?php $menu = 6; require "menu.php"; ?>
  <div id="newuser">
    会員登録<BR>
    <?php $app->form("inquerydo.php", true, array(), array(), true); ?>
    <table>
    <tr>
    <td>件名</td><td><input name="subject" size="32" placeholder="件名を入力してください（必須）"></td>
    </tr>
    <tr>
    <td>Eメール</td><td><input name="email" size="32" placeholder="メールアドレス（必須）"></td>
    </tr>
    <tr>
    <td>氏名</td><td><input name="name" size="32" placeholder="氏名（必須）"></td>
    </tr>
    <tr>
    <td>質問内容</td><td><textarea name=question cols="40" rows="10" placeholder="お問い合わせ内容（必須）"></textarea>
    </tr>
    <tr>
    <td></td><td><input type=submit value="送信"></td>
    </tr>
    </table>
    </form>
  </div><!-- /#newuser -->
<?php require "footer.php"; ?>
</div>
</body>
</html>
