<?php
  require_once('./common.php');
  $reqid = filter_input(INPUT_GET, 'id');
  $ok = true;
  try {
    $dbh = dblogin();
    $sql = "SELECT id, userid, pwd, email, icon, super FROM users WHERE id=?";
    $sth = $dbh->prepare($sql);
    $sth->execute(array($reqid));
    $result = $sth->fetch(PDO::FETCH_ASSOC);
    if (empty($result)) {
      $ok = false;
    } else {
      $userid = $result['userid']; 
      $email = $result['email'];
      $pwd   = $result['pwd'];
      $icon  = $result['icon'];
      $super = $result['super'];
    }
  } catch (PDOException $e) {
    $app->addlog('クエリに失敗しました: ' . $e->getMessage());
    error_exit();
  }
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>マイページ</title>
</head>
<body>
<div id="top">
<?php $menu = 5; require "menu.php"; ?>
  <div id="contents">
  <?php if ($ok) : ?>
    <table style="width: 70%;">
    <tr>
    <td>ID</td><td><?php e($userid); ?></td>
    </tr>
    <tr>
    <td>メールアドレス</td><td><?php e($email); ?><?php $app->a('changemail.php', '変更', false, array('id' => $reqid)); ?></td>
    </tr>
    <tr>
    <td>パスワード</td><td>******<?php $app->a('changepwd.php', '変更', false, array('id' => $reqid)); ?></td>
    </tr>
    <tr>
    <td>アイコン</td><td><img src="resize.php?path=icons&basename=<?php e($icon); ?>&size=64">
      <?php $app->a('changeicon.php', '変更', false, array('id' => $reqid)); ?>  
    </td>
    </tr>
    <?php if ($app->is_super()): ?>
      <tr>
      <td>利用者権限</td><td><?php e($super ? '管理者' : '一般'); ?></td>
      </tr>
    <?php endif; ?>
    </table>
    <?php $app->a('editprofile.php', '変更', true, array('id' => $reqid)); ?>  
    <?php $app->a('quit.php', '退会', true, array('id' => $reqid)); ?>  
  <?php else : ?>
    権限がないか、そのユーザは存在しません
  <?php endif; ?>
  </div><!-- /#contents -->
<?php require "footer.php"; ?>
</div>
</body>
</html>
