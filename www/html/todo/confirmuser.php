<?php
require_once('./common.php');
$errmsg = array();

try {
  $dbh = dblogin();

  $userid = filter_input(INPUT_POST, "id");
  if (! ereg('^[a-zA-Z0-9]{3,16}$', $userid)) {
    $errmsg[] = "ユーザIDは英数字で3文字以上、16文字以内で指定してください";
  } else {
    $sql = "SELECT COUNT(*) FROM users WHERE id=?";
    $sth = $dbh->prepare($sql);
    $sth->execute(array($userid));
    if ($sth->fetchColumn() > 0) {
      $errmsg[] = "このユーザID($userid)は既に登録されています";
    }
  }
  $pwd   = filter_input(INPUT_POST, "pwd");
  if (mb_strlen($pwd) < 4) {
    $errmsg[] = "パスワードは4文字以上で指定してください";
  }
  $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
  if (! $email) {
    $errmsg[] = "メールアドレスの形式が不正です";
  } else {
    $sql = "SELECT COUNT(*) FROM users WHERE email=?";
    $sth = $dbh->prepare($sql);
    $sth->execute(array($email));
    if ($sth->fetchColumn() > 0) {
      $errmsg[] = "このメールアドレス($email)は既に登録されています";
    }
  }
  $icon  = $_FILES["icon"];
  if ($icon['error'] !== 0) {
    $errmsg[] = 'アイコン画像を指定してください';
  } else {
    $tmp_name = $icon["tmp_name"];
    $iconfname = $icon["name"];
    if (! image_file($iconfname)) {
      $errmsg[] = 'アイコン画像の拡張子は png/jpg/jpeg のいずれかを指定ください';
    } else {
      $iconrealfname = uniqid() . '-' . $iconfname;
      move_uploaded_file($tmp_name, "temp/$iconrealfname");
    }
  }
  $super = filter_input(INPUT_POST, 'super', FILTER_VALIDATE_BOOLEAN);
} catch (PDOException $e) {
  $app->addlog('クエリに失敗しました: ' . $e->getMessage());
  error_exit();
}

?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>会員登録</title>
</head>
<body>
<div id="top">
  <?php require "menu.php"; ?>
  <div id="contents">
    <?php if (empty($errmsg)): ?>
      入力を確認してください<BR>
      <?php $app->form("adduser.php"); ?>
        <table style="width: 50%;">
          <tr>
          <td>ユーザID</td><td><?php e($userid); ?></td>
          </tr>
          <tr>
          <td>パスワード</td><td>********</td>
          </tr>
          <tr>
          <td>Eメール</td><td><?php e($email); ?></td>
          </tr>
          <tr>
          <td>アイコンファイル</td><td><img src="resize.php?path=temp&basename=<?php e($iconrealfname); ?>&size=64"></td>
          </tr>
          <?php if ($app->is_super()): ?>
            <tr>
            <td>ユーザ種別</td><td><?php echo $super ? "管理者" : "一般ユーザ"; ?></td>
            </tr>
          <?php endif; ?>
          <tr>
          <td></td><td><input type=submit value="登録"></td>
          </tr>
        </table>
        <?php
          foreach ($_POST as $key => $value) {
            echo '<input name="' . $key . '" type="hidden" value="' . h($value) . "\">\n";
          }
          echo '<input name="iconfname" type="hidden" value="' . h($iconrealfname) . '">';
        ?>
      </form>
    <?php else:
      foreach ($errmsg as $msg) {
        echo "$msg<br>";
      }
      echo '<br><button type="button" onclick="window.history.back();">戻る</button>';  
    endif; ?>
   </div><!-- #confirm -->
  <?php require "footer.php"; ?>
</div> <!-- #top -->
</body>
</html>
