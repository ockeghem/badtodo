<?php
  require_once './common.php';
  try {
    $rnd = uniqid();
    $dbh = dblogin();
    $userid = filter_input(INPUT_POST, 'userid');
    $pwd = mb_substr(filter_input(INPUT_POST, 'pwd'), 0, 6);
    $url = filter_input(INPUT_POST, 'url');
    $autologin = filter_input(INPUT_POST, 'autologin', FILTER_VALIDATE_BOOLEAN);
    
    $sql = "SELECT id, userid FROM users WHERE userid='$userid'";
    $sth = $dbh->query($sql);
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    $sth = null;
    if (! empty($row)) {
      $sql = "SELECT id, userid, super FROM users WHERE userid='$userid' AND pwd='$pwd'";
      $sth = $dbh->query($sql);
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      if (! empty($row)) {
        $user = $app->new_user($row['id'], $userid, $row['super'], true);
        if ($autologin) {
          badsetcookie('AUTOLOGIN', serialize($user), time() + 3600 * 24 * 365);
        }
        header('Location: ' . $url . '?rnd=' . h($rnd) . '&' . $app->sid());
      } else {
        error_exit("パスワードが違います", true);
      }
    } else {
      error_exit("そのユーザーは登録されていません", true);
    }
  } catch (PDOException $e) {
    error_exit('接続に失敗しました: ' . $e->getMessage(), true);
  }
?><body>
ログイン成功しました<br>
自動的に遷移しない場合は以下のリンクをクリックして下さい。
<a href="<?php echo "$url?rnd=" . h($rnd); ?>">todo一覧に遷移</a>        
</body>
