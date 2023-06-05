<?php
  require_once('./common.php');
  $app->require_loggedin();
  $id = $app->get_id();
  $item = filter_input(INPUT_GET, 'item');
  if (empty($item)) {
    header('Location: todolist.php');
    exit;
  }
  try {
    $dbh = dblogin();
    $sql = "SELECT todos.id, users.userid, todo, c_date, due_date, done, memo, org_filename, real_filename, url, url_text, public FROM todos INNER JOIN users ON todos.id = ? AND users.id = todos.owner AND (todos.owner = ? OR ? )";
    $sth = $dbh->prepare($sql);
    $sth->execute(array($item, $id, $app->is_super()));
    $result = $sth->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    $app->addlog('クエリに失敗しました: ' . $e->getMessage());
    error_exit();
  }
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>Todo編集</title>
<script src="./js/jquery-1.8.3.js"></script>
<script src="./js/purify.min.js"></script>
<script src="./js/preview.js"></script>
</head>
<body>
<div id="top">
<?php require "menu.php"; ?>
  <!-- <div id="contents"> -->
  <div id="newtodo" style="display: flex;">
    <?php if (! empty($result)): ?>
      <div style="width: 55%">
        <?php $app->form("editdone.php", true, array("item" => $item), array("enctype" => "multipart/form-data"), true);  ?>
        <table>
        <tr>
        <td>todo</td><td><input name="todo" value="<?php e($result['todo']); ?>" size="30" placeholder="todoを入力してください（必須）"></td>
        </tr>
        <tr>
        <td>登録日</td><td><input name="c_date" value="<?php e($result['c_date']); ?>" type="date"></td>
        </tr>
        <tr>
        <td>期限</td><td><input name="due_date" value="<?php e($result['due_date']); ?>" type="date"></td>
        </tr>
        <tr>
        <td>公開</td><td><input type="checkbox" name="public" value="1" <?php if ($result['public']) e('checked="checked"'); ?>></td>
        </tr>
        <tr>
        <td>完了</td><td><input type="checkbox" name="done" value="1" <?php if ($result['done']) e('checked="checked"'); ?>></td>
        </tr>
        <tr>
        <td>メモ</td><td><textarea name="memo" cols="30" rows="5" placeholder="補足事項（任意）"><?php e($result['memo']); ?></textarea></td>
        </tr>
        <tr>
        <td>添付ファイル</td><td><?php e($result['org_filename']); ?> <input name="attachment" type="file"></td>
        </tr>
        <tr>
        <td>URL</td><td><input type="text" name="url" value="<?php e($result['url']); ?>" id="input-url" size="30" placeholder="補足URL（任意）"></td>
        </tr>
        <tr>
        <td>URL（タイトル）</td><td><input type="text"  id="input-linktext" name="url_text"  value="<?php e($result['url_text']); ?>" size="30" placeholder="URLの表示文字列（任意）"></td>
        </tr>
        </table><br>
        <input type="submit" value="更新">    
        </form>
      </div>
      <div class="preview" id="preview"></div>
    <?php else: ?>
      選択された項目は存在しないか、権限がありません。
      <br><br><button type="button" onclick="window.history.back();">戻る</button>
    <?php endif; ?>
  </div>
<?php require "footer.php"; ?>
</div>
</body>
</html>
