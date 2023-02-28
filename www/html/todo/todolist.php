<?php
  require_once('./common.php');
  $errmsg = array();
  $id = $app->get_id();
  if (empty($id))
    $id = -1;
  $reqid = filter_input(INPUT_GET, 'id');
  $key   = filter_input(INPUT_GET, 'key');
  $like_search = filter_input(INPUT_GET, 'like-search', FILTER_VALIDATE_BOOLEAN);
  if (empty($reqid))
    $reqid = -1;

  try {
    $dbh = dblogin();
    $sql = "SELECT todos.id, owner, users.userid, todo, c_date, due_date, done, org_filename, real_filename, public FROM todos INNER JOIN users ON users.id=todos.owner AND (todos.owner=$reqid OR ?) AND (todos.owner = ? OR todos.public > 0 OR ? > 0)";
    if (! empty($key) && $like_search) {
      $sql .= " AND todo LIKE '%$key%'";
    } elseif (! empty($key)) {
      $sql .= " AND todo = '$key'";
    }
    $sth = $dbh->prepare($sql);
    $sth->execute(array($reqid < 0, $id, $app->is_super()));
    $rows = $sth->fetchAll();
  } catch (PDOException $e) {
    $app->addlog('クエリに失敗しました: ' . $e->getMessage());
    $errmsg[] = "検索時にエラーが発生しました\n $sql\n" . $e->getMessage();
  }
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<script src="./js/jquery-1.8.3.js"></script>
<title>一覧</title>
</head>
<body>
<div id="top">
<?php $menu = 1; require "menu.php"; ?>
  <div id="search">
    <!-- <?php $app->form("", false); ?> -->
    <?php $app->form($_SERVER['PHP_SELF'], false); ?>
      <input type="text" name="key" value="<?php echo $key; ?>">
      <input type="submit" value="検索">
      <label for="like-search">あいまい検索</label>
      <input type="checkbox" name="like-search" value="1" id="like-search" <?php echo $like_search ? ' checked' : ''?>>
    </form>
  </div>
  <div id="contents">
    <?php if (empty($errmsg)): ?>
      <?php $app->form("editlist.php", true, array(), array(), true); ?>
        <table>
        <tr>
        <th><input type="checkbox" onclick="checkOrClearAll(this)"></th>
        <th>ID</th>
        <th>todo</th>
        <th>登録日</th>
        <th>期限</th>
        <th>完了</th>
        <th>添付ファイル</th>
        <th>公開</th>
        </tr>
        <?php
          foreach ($rows as $row) :
        ?><tr>
        <td><input type="checkbox" name="id[]" value="<?php e($row['id']); ?>"></td>
        <td><?php $app->a('todolist.php', h($row['userid']), true, array('id' => $row['owner']), array()); ?>      
        </td>
        <td><?php $app->a('todo.php', 
                        $row['done'] ? '<s>' . h($row['todo']) . '</s>' : h($row['todo']),
                        true, array('item' => $row['id']), array()); ?>  
        </td>
        <td><?php e($row['c_date']); ?></td>
        <td><?php e($row['due_date']); ?></td>
        <td><?php e($row['done'] ? '完' : ''); ?></td>
        <td><?php
          if (!empty($row['org_filename'])) {
            echo "<a target=\"_blank\" href=\"attachment/${row['real_filename']}\" download=\"${row['org_filename']}\">${row['org_filename']}</a>";
          } 
        ?></td>
        <td><?php e($row['public'] ? 'OK' : ''); ?></td>
        </tr>
        <?php
            endforeach;
        ?>
        </table><br>
        <button type="submit" name="process" value="dellist">削除</button>
        <button type="submit" name="process" value="donelist">完了</button>
        <button type="submit" name="process" value="exportlist">エクスポート</button>
      </form>
    <?php else:
      foreach ($errmsg as $msg) {
        echo "$msg<br>";
      }
      echo '<br><button type="button" onclick="window.history.back();">戻る</button>';
    endif; ?>
    <br>
  </div><!-- /#contents -->
<?php require "footer.php"; ?>
</div>
<script>
function checkOrClearAll(checkbox) {
  $('input[name="id[]"]').prop('checked', checkbox.checked);
}

function check() {
  var checklist = decodeURIComponent (location.hash.slice(1));
  if (checklist === 'all') {
    $('input[name="id[]"]').prop('checked', true);
  } else {
    var a = checklist.split(',');
    a.map(function(id) {
      $('input[name="id[]"][value="' + id + '"]').prop('checked', true);
    });
  }
}

$(function() {
  window.addEventListener("hashchange", check, false);
  check();
});
</script>
</body>
</html>
