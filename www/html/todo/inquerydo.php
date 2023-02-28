<?php
require_once './common.php';
$app->require_token();
$errmsg = array();

$email = filter_input(INPUT_POST, "email");
if (! validEmailAddress($email)) {
  $errmsg[] = 'メールアドレスの形式が不正です';
}
$name  = filter_input(INPUT_POST, "name");
if (empty($name)) {
  $errmsg[] = '氏名を入力してください';
}
$subject = filter_input(INPUT_POST, "subject");
if (empty($subject)) {
  $errmsg[] = '件名を入力してください';
}
$question  = filter_input(INPUT_POST, "question");
if (empty($question)) {
  $errmsg[] = '質問内容を入力してください';
}

if (empty($errmsg)) {
  $descriptorspec = array(
    0 => array("pipe", "r"),
    1 => array("pipe", "w"),
    2 => array("file", "/tmp/php-error-output.txt", "a"));

  $process = proc_open("/usr/sbin/sendmail -i \"$email\"", $descriptorspec, $pipes);
  if (is_resource($process)) {
    fputs($pipes[0], "From: contact@example.jp\n");
    fputs($pipes[0], "To: $email\n");
    fputs($pipes[0], "Subject: お問い合わせ($subject)を受け付けました\n");
    fputs($pipes[0], "Mime-Version: 1.0\n");
    fputs($pipes[0], "Content-Type: text/plain; charset=\"UTF-8\"\n");
    fputs($pipes[0], "Content-Transfer-Encoding: 8bit\n");
    fputs($pipes[0], "\n");
    fputs($pipes[0],  
    "毎々お引き立て頂きありがとうございます。\n" .
    "以下のお問い合わせを受け付けましたのでご確認くださいませ。\n\n" .
    $question);
    fputs($pipes[0], ".\n");
    fclose($pipes[0]);

    e(stream_get_contents($pipes[1]));
    fclose($pipes[1]);

    $return_value = proc_close($process);
  }
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>問い合わせ</title>
</head>
<body>
<div id="top">
<?php $menu = 6; require "menu.php"; ?>
  <div id="done">
  <?php if (empty($errmsg)): ?>
    お問合せを受け付けました。<BR><BR>
    <?php else: 
      foreach ($errmsg as $msg) {
        echo "$msg<br>";
      }
      echo '<br><button type="button" onclick="window.history.back();">戻る</button>';
    endif; ?>
  </div><!-- /#done -->
<?php require "footer.php"; ?>
</div>
</body>
</html>
