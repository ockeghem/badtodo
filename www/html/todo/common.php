<?php
define('SESSIDNAME', 'TODOSESSID');
define('TOKENNAME', 'todotoken');
define('TOKENLEN', 16);

require_once 'badapp.php';

class User {
  private $id;
  private $userid;
  private $super;

  function __construct($id = false, $userid = false, $super = 0) {
    $this->id = $id;
    $this->userid = $userid;
    $this->super = $super;
  }
  public function get_id() {
    return $this->id;
  }
  public function get_userid() {
    return $this->userid;
  }
  public function is_super() {
    return $this->super ? 1 : 0;
  }
} // End User

class Logger {
  const LOGDIR = '/var/www/html/todo/logs/';  // ログ出力ディレクトリ
  private $filename = '';  // ログファイル名
  private $log = '';       // ログバッファ

  public function __construct($filename) {  // コンストラクタ…ファイル名を指定
    $this->filename = basename($filename); // ファイル名
    $this->log = '';             // ログバッファ
  }

  public function __destruct() { // デストラクタではバッファの中身をファイルに書き出し
    if (empty($this->log))
      return;
    $path = self::LOGDIR . $this->filename;  // ファイル名の組み立て
    $fp = fopen($path, 'a+');
    if ($fp === false) {
      error_exit('Logger: ファイルがオープンできません' . htmlspecialchars($path), false, '500 Internal Server Error');
    }
    fwrite($fp, $this->log); // ログの書き出し
    fclose($fp);
  }

  public function add($log) {  // ログ出力
    $this->log .= date("Y/m/d H:i:s") . " : " . $_SERVER['SCRIPT_FILENAME'] . ":" . $log . "\n";        // バッファに追加するだけ
  }
} // End Logger

function error_exit($content = "只今サイトが大変混雑しています。もうしばらく経ってからアクセスしてください", $back = false, $status = false) {
  if ($status !== false) {
    header("Status: $status");
    exit($content);
  }
  $title = "エラー";
  if ($back)
    $content .= '<br><button type="button" onclick="window.history.back();">戻る</button>';
  require "template.php";
  exit;
}

function h($s)
{
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function e($s)
{
  echo htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function dblogin() {
  $dbhost = isset($_ENV['REDIRECT_MYSQL_HOST']) ? $_ENV['REDIRECT_MYSQL_HOST'] : '127.0.0.1';
  $dbh = new PDO("mysql:host=$dbhost;dbname=todo", 'root', 'wasbook');
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbh->query("SET NAMES utf8");
  return $dbh;
}

function safe_file($filename) {
  $unsafe_ext_array = array('php', 'html', 'htm', 'exe', 'bat', 'sh', 'pl', 'ps1', 'cmd');
  $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
  return ! in_array($ext, $unsafe_ext_array);
}

function image_file($filename) {
  $img_ext_array = array('jpeg', 'jpg', 'png');
  $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
  return in_array($ext, $img_ext_array);
}

function badsetcookie($key, $value, $expire = 0, $secure = false) {
  if (is_https() && $secure) {
    setcookie($key, $value, $expire, '/; samesite=none', null, true);
  } else {
    setcookie($key, $value, $expire, '/');
  }
}

// RFC準拠のメールアドレス検証
function validEmailAddress($email) {
  return preg_match(
    '/\A(?!(?>(?1)"?(?>\\\[ -~]|[^"])"?(?1)){255,})(?!(?>(?1)"?(?>\\\[ -~]|[^"])"?(?1)){65,}@)((?>(?>(?>((?>(?>(?>\x0D\x0A)?[\t ])+|(?>[\t ]*\x0D\x0A)?[\t ]+)?)(\((?>(?2)(?>[\x01-\x08\x0B\x0C\x0E-\'*-\[\]-\x7F]|\\\[\x00-\x7F]|(?3)))*(?2)\)))+(?2))|(?2))?)([!#-\'*+\/-9=?^-~-]+|"(?>(?2)(?>[\x01-\x08\x0B\x0C\x0E-!#-\[\]-\x7F]|\\\[\x00-\x7F]))*(?2)")(?>(?1)\.(?1)(?4))*(?1)@(?!(?1)[a-z0-9-]{64,})(?1)(?>([a-z0-9](?>[a-z0-9-]*[a-z0-9])?)(?>(?1)\.(?!(?1)[a-z0-9-]{64,})(?1)(?5)){0,126}|\[(?:(?>IPv6:(?>([a-f0-9]{1,4})(?>:(?6)){7}|(?!(?:.*[a-f0-9][:\]]){8,})((?6)(?>:(?6)){0,6})?::(?7)?))|(?>(?>IPv6:(?>(?6)(?>:(?6)){5}:|(?!(?:.*[a-f0-9]:){6,})(?8)?::(?>((?6)(?>:(?6)){0,4}):)?))?(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])(?>\.(?9)){3}))\])(?1)\z/isD',
    $email
  );
}

function is_https() {
  return strpos(@$_SERVER['HTTP_FORWARDED'], 'proto=https') !== false;
}
if (! isset($nosession)) {
  $app = new BadApp();
  $app->check_login();
}