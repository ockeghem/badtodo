<?php
  require_once('./common.php');
  $app->require_loggedin();  // added on Ver 2.0.0
  try {
    $dbh = dblogin();
    $sql = "SELECT id, userid, pwd, email, icon, super FROM users";
    $sth = $dbh->query($sql);
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>ユーザ一覧</title>
</head>
<body>
<div id="top">
<?php $menu = 7; require "menu.php"; ?>
  <div id="contents">
    <table>
    <tr>
    <th>ID</th>
    <th>パスワード</th>
    <th>メールアドレス</th>
    <th>アイコン</th>
    <th>種別</th>
    </tr>
    <?php
      foreach ($sth as $row) :
    ?><tr>
    <td>
    <?php $app->a('profile.php', $row['userid'], true, array('id' => $row['id'])); ?></td>
    <td>
    <?php $app->a('changepwd.php', h($row['pwd']), true, array('id' => $row['id'])); ?></td>
    <td>
    <?php $app->a('changemail.php', $row['email'], true, array('id' => $row['id'])); ?></td>
    <td><img src="resize.php?path=icons&basename=<?php e($row['icon']); ?>&size=64">
    <?php $app->a('changeicon.php', '変更', true, array('id' => $row['id'])); ?></td>
    <td><?php e($row['super'] ? '管理者' : '一般'); ?></td>
    </tr><?php
        endforeach;
      } catch (PDOException $e) {
        $app->addlog('クエリに失敗しました: ' . $e->getMessage());
        error_exit();
      }
    ?>
    </table><br>
    <?php $app->a('newuser.php', '新規追加'); ?><br>
    <br>
  </div><!-- /#contents -->
<?php require "footer.php"; ?>
</div>
</body>
</html>
