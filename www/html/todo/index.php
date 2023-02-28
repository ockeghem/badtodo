<?php
  require_once './common.php';
  $app->send_session_cookie();
  $rnd = uniqid();
?><html>
<head><title>実習ガイド</title></head>
<body>
<h2>目次</h2>
<ul>
<?php if ($_SERVER['HTTP_HOST'] == 'todo.example.jp'): ?>
  <li><a href="/todolist.php?rnd=<?php e($rnd); ?>">Bad Todo List（やられサイト本体）</a></li>
  <li><a href="/mail/">Webメール(MailCatcher)</a></li>
  <li><a href="/adminer.php?server=db&username=root">adminer</a></li>
  <li><a href="https://trap.example.org/">罠サイト（中身は実習にて作成）</a></li>
  <li><a href="phpinfo.php">phpinfo</a></li>
<?php else: ?>
  <li><a href="/todo/todolist.php?rnd=<?php e($rnd); ?>">Bad Todo List（やられサイト本体）</a></li>
  <li><a href="/mail/">Webメール(MailCatcher)</a></li>
  <li><a href="/adminer.php?server=db&username=root">adminer</a></li>
  <li><a href="/trap/">罠サイト（中身は実習にて作成）</a></li>
  <li><a href="phpinfo.php">phpinfo</a></li>
<?php endif; ?>
</ul>
<h2>組み込みのユーザ名/パスワード</h2>
  <ul>
    <li>admin/passwd （管理者）</li>
    <li>wasbook/wasbook （一般ユーザ）</li>
  </ul>
<h2>
  MariaDBのパスワード（Adminerにて使用）</h2>
  <ul>
  <li>root/wasbook</li>
  </ul>
</body>
</html>
