<?php
  require_once('./common.php');
  $id = $app->get_id();
  $item = filter_input(INPUT_GET, 'item');

  try {
    $dbh = dblogin();
    $sql = "SELECT todos.id, users.userid, users.icon, todo, c_date, due_date, done, memo, org_filename, real_filename, url, url_text, public FROM todos INNER JOIN users ON todos.id = ? AND users.id = todos.owner";
    $sth = $dbh->prepare($sql);
    $sth->execute(array($item));
    $result = $sth->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    $app->addlog('クエリに失敗しました: ' . $e->getMessage());
    error_exit();
  }
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>Todo詳細</title>
</head>
<body>
<div id="top">
<?php require "menu.php"; ?>
  <div id="contents">
    <?php if (! empty($result)): ?>
      <table style="width: 70%;">
      <tr>
      <td>ID</td><td><?php e($result['userid']); ?><img src="resize.php?path=icons&basename=<?php e($result['icon']); ?>&size=64"></td>
      </tr>
      <tr>
      <td>todo</td><td><?php e($result['todo']); ?></td>
      </tr>
      <tr>
      <td>登録日</td><td><?php e($result['c_date']); ?></td>
      </tr>
      <tr>
      <td>期限</td><td><?php e($result['due_date']); ?></td>
      </tr>
      <tr>
      <td>完了</td><td><?php e($result['done'] ? '完了' : '未'); ?></td>
      </tr>
      <tr>
      <td>メモ</td><td><?php 
        $memo = nl2br(h($result['memo']));
        $memo = preg_replace('|https?://[a-zA-Z0-9\+\$\;\?\.%,!#~*/:@&=_-]+|', '<a href="${0}">${0}</a>', $memo);
        echo $memo;
      ?></td>
      </tr>
      <tr>
      <td>添付ファイル</td><td><?php if (! empty($result['org_filename'])): ?>
        <a href="attachment/<?php echo h($result['real_filename']) . '" download="' . h($result['org_filename']) . '">' . h($result['org_filename']); ?></a>
        <?php $app->form("delfile.php", true, array("item" => $item), array("style" => "display:inline;"), true); ?>
        <input type="submit" value="削除">
        </form>
        <?php endif; ?>
      </tr>
      <tr>
      <td>URL</td><td><a href="<?php e($result['url']); ?>">
        <?php empty($result['url_text']) ? e($result['url']) : e($result['url_text']); ?></a></td>
      </tr>
      <tr>
      <td>公開</td><td><?php e($result['public'] ? '公開' : '未公開'); ?></td>
      </tr>
      </table>
      <?php $app->form("edittodo.php", false, array('item' => $result['id'])); ?>
      <input type="submit" value="編集">
      </form>
      <?php else: ?>
      選択された項目は存在しないか、権限がありません。
      <br><br><button type="button" onclick="window.history.back();">戻る</button>
    <?php endif; ?>
  </div><!-- /#contents -->
<?php require "footer.php"; ?>
</div>
</body>
</html>
