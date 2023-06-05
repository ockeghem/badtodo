<?php
  global $app;  // 状況により関数内から呼ばれる場合があるので global 宣言しておく
?><div id="header">
  <h1><?php $app->a('todolist.php', 'Bad Todo List'); ?></h1>
</div><!-- /#header-->
<div class="welcome">
<?php
  if ($app->is_loggedin()) {
    $current_user = $app->get_userid();
    if ($app->is_super())
      $current_user .= '(管理者)';
    e("こんにちは、$current_user さん");
  } else {
    e("こんにちは、ゲストさん");
  }
  if (! isset($menu)) $menu = 0;
?>
</div><!-- /#welcome -->
<div id="menu">
  <ul>
  <li><?php $app->a('todolist.php', '一覧',       true, array(), array('class' => ($menu === 1 ? 'on' : ''))); ?></li>
  <li><?php $app->a('newtodo.php', '新規追加',    true, array(), array('class' => ($menu === 2 ? 'on' : ''))); ?></li>
  <li><?php $app->a('import.php', 'インポート',   true, array(), array('class' => ($menu === 3 ? 'on' : ''))); ?></li>
  <li><?php $app->a('export.php', 'エクスポート', true, array(), array('class' => ($menu === 4 ? 'on' : ''))); ?></li>
<?php if ($app->is_loggedin()): ?>
  <li><?php $app->a('profile.php', 'マイページ', true, array('id' => $app->get_id()), array('class' =>($menu === 5 ? 'on' : ''))); ?></li>
<?php  endif ?>
  <li><?php $app->a('inquery.php', '問い合わせ', true, array(), array('class' => ($menu === 6 ? 'on' : ''))); ?></li>
<?php if ($app->is_super()): ?>
  <li><?php $app->a('userlist.php', '会員一覧', true, array(), array('class' => ($menu === 7 ? 'on' : ''))); ?></li>
<?php  endif ?>
  <li><?php
  if ($app->is_loggedin()) {
    $app->a('logout.php', 'ログアウト', false, array(), array('class' => ($menu === 8 ? 'on' : '')));
  } else {
    $app->a('login.php', 'ログイン', false, array('url' => 'todolist.php'), array('class' => ($menu === 8 ? 'on' : '')));
  } ?></li>
  </ul>
</div><!-- /#menu-->
<br>
