<?php
  require_once('./common.php');
  $app->require_loggedin();
  $id = $app->get_id();
  $token = $app->get_token();
  $reqid = filter_input(INPUT_GET, 'id');
  if (empty($reqid))
    $reqid = $id;
  try {
    $dbh = dblogin();
    $sql = "SELECT userid, email FROM users WHERE id=?";
    $sth = $dbh->prepare($sql);
    $sth->execute(array($reqid));
    $result = $sth->fetch(PDO::FETCH_ASSOC);
    $requserid = $result['userid'];
    $email  = $result['email'];
  } catch (PDOException $e) {
    $app->addlog('クエリに失敗しました: ' . $e->getMessage());
    error_exit();
  }
?><html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<title>メールアドレス変更</title>
<script src="./js/jquery-1.8.3.js"></script>
</head>
<body>
<div id="top">
<?php require "menu.php"; ?>
  <div id="newuser">
    メールアドレス変更(<?php e($requserid); ?>)<BR>
    <?php $app->form("changemaildo.php", true, array(), array(), true); ?>
    <table>
    <tr>
    <td>Eメール</td><td><input name="email" id="input-email" size="32"><span id="out-email" class="message"></span></td>
    </tr>
    <tr>
    <td></td><td><input type=submit value="変更"></td>
    </tr>
    </table>
    <?php if ($reqid !== $id) : ?>
      <input type="hidden" name="id" value="<?php e($reqid); ?>">
    <?php endif; ?>
    </form>
  </div><!-- /#newuser -->
<?php require "footer.php"; ?>
</div>
<script>
  $(function() {
    $("#input-email").change(function() {
      const email = $(this).val()
      $.ajax({
        url: "api/v1/is_valid_email.php",
        type: "get",
        data: {"email": email},
        dataType: "json",
      }).done(function(result){
        if (result.ok) {
          $("#out-email").html('')
        } else {
          $("#out-email").html(result.message)
        }
      })
    })
  })
</script>
</body>
</html>
