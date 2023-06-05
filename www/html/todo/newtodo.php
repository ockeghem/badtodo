<?php
require_once './common.php';

$app->require_loggedin();
$token = $app->get_token();
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>Todo追加</title>
<script src="./js/jquery-1.8.3.js"></script>
<script src="./js/purify.min.js"></script>
<script src="./js/preview.js"></script>
</head>
<body>
<div id="top">
<?php $menu = 2; require "menu.php"; ?>
  <div id="newtodo" style="display: flex;">
    <div style="width: 55%">
    todo新規登録<BR>
    <?php $app->form("addtodo.php", true, array(), array("enctype" => "multipart/form-data"), true); ?>
    <table>
    <tr>
    <td>todo</td><td><input name="todo" size="30" placeholder="todoを入力してください（必須）"></td>
    </tr>
    <tr>
    <td>期限</td><td><input name="due_date" size="16" type="date"></td>
    </tr>
    <tr>
    <td><label for="public">公開</label></td><td><input name="public" id="public" type="checkbox" value="1"></td>
    </tr>
    <tr>
    <td>メモ</td><td><textarea name="memo" cols="30" rows="5" placeholder="補足事項（任意）"></textarea></td>
    </tr>
    <tr>
    <td>添付ファイル</td><td><input type="file" name="attachment"></td>
    </tr>
    <tr>
    <td>URL</td><td><input type="text" name="url" size="30" placeholder="補足URL（任意）" id="input-url"></td>
    </tr>
    <tr>
    <td>URL（タイトル）</td><td><input type="text" name="url_text" id="input-linktext" size="30" placeholder="URLの表示文字列（任意）"></td>
    </tr>
    <tr>
    <td></td><td><input type="submit" value="登録"></td>
    </tr>
    </table>
    </form>
    </div>
    <div class="preview" id="preview">
    </div>
  </div><!-- /#newuser -->
<?php require "footer.php"; ?>
</div>
</body>
</html>
