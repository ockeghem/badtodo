<?php
  require_once('./common.php');
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>会員登録</title>
<script src="./js/jquery-1.8.3.js"></script>
</head>
<body>
<div id="top">
<?php require "menu.php"; ?>
<div id="newuser">
会員登録<BR>
<?php $app->form("confirmuser.php", true, array(), array("enctype" => "multipart/form-data")); ?>
<table>
<tr>
<td>ユーザID</td><td><input name="id" id="input-id" size="16" placeholder="ユーザID（必須）"><span id="out-id" class="message"></span></td>
</tr>
<tr>
<td>パスワード(8文字以内)</td><td><input name="pwd" id="input-pwd" type="password" size="16" placeholder="パスワード（必須）"><span id="out-pwd" class="message"></span></td>
</tr>
<tr>
<td>Eメール</td><td><input name="email" id="input-email" size="32" placeholder="メールアドレス（必須）"><span id="out-email" class="message"></span></td>
</tr>
<tr>
<td>アイコン画像(PNG, JPEG)</td><td><input name="icon" type="file"></td>
</tr>
<?php if ($app->is_super()): ?>
  <tr>
  <td>管理者</td><td><input type="checkbox" name="super" value="1"></td>
  </tr>
<?php endif; ?>
<tr>
<td></td><td><input type=submit value="確認"></td>
</tr>
</table>
</form>
  </div><!-- /#newuser -->
<?php require "footer.php"; ?>
</div>
<script>
  $(function() {
    $("#input-id").change(function() {
      const userid = $(this).val()
      $.ajax({
        url: "api/v1/is_valid_id.php",
        type: "get",
        data: {"id": userid},
        dataType: "json",
      }).done(function(result) {
        if (result.ok) {
          $("#out-id").html('')
        } else {
          $("#out-id").html(result.message)
        }
      })
    })
    $("#input-pwd").change(function() {
      const pwd = $(this).val()
      if (pwd.length >= 4 && pwd.length <= 8) {
        $("#out-pwd").html('')
      } else {
        $("#out-pwd").html('パスワードは4文字以上、8文字以下で指定してください')
      }
    })
    $("#input-email").change(function() {
      const email = $(this).val()
      $.ajax({
        url: "api/v1/is_valid_email.php",
        type: "get",
        data: {"email": email},
        dataType: "json",
      }).done(function(result) {
        if (result.ok) {
          $("#out-email").html('')
        } else {
          $("#out-email").html(result.message)
        }
      })
    })
  })
</script>
</body>
</html>
