<?php
define('FATAL_SESSION_ERROR', '致命的エラー:セッション管理でエラー発生');
define('MAX_SESSION_COUNT', 100);
define('MAGICNUMBER', 4199870312793);

function get_randomid() {
  $random = microtime(true) + MAGICNUMBER;
  return md5($random);
}

class DbSessionManager
{
  protected $log;  // デバッグ用
  protected $sessionid;
  private $dict;
  private $lifetime;
  private $dbh = null;
  private $alive;
  protected $cookie_avail = false;

  function __construct() {
    $this->log = 'construct:';
    $this->dict = array();
    $this->set_sessionid();
    $this->open();
    $this->read();
    $this->alive = true;
    register_shutdown_function(array($this, 'write_close'));
  }
  function __destruct() {
    // var_dump($this->log);    // デバッグ時には有効にする
  }
  public function write_close() {
    $this->write();
    $this->close();
  }
  public function sid() {
    $this->log .= "sid:";
    return SESSIDNAME . '=' . $this->sessionid;
  }
  public function get($key) {
    return isset($this->dict[$key]) ? $this->dict[$key] : null;
  }

  public function set($key, $value) {
    $this->dict[$key] = $value;
  }

  private function set_sessionid() {
    $this->log .= "set_sessionid:";
    if (isset($_COOKIE[SESSIDNAME])) {
      $this->sessionid = $_COOKIE[SESSIDNAME];
      $this->cookie_avail = true;
    } elseif (isset($_GET[SESSIDNAME])) {
      $this->sessionid = $_GET[SESSIDNAME];
    } elseif (isset($_POST[SESSIDNAME])) {
      $this->sessionid = $_POST[SESSIDNAME];
    } else {
      $this->sessionid = get_randomid();
    }
  }
  public function send_session_cookie() {
    $this->log .= "send_session_cookie:";
    if (! $this->cookie_avail && ! empty($this->sessionid)) {
      badsetcookie(SESSIDNAME, $this->sessionid, 0, true);
    }
  }
  private function open($savePath = null, $sessionName = null) {
    $this->lifetime = ini_get("session.gc_maxlifetime");
    $this->dbh = dblogin();
    $this->log .= "open:{$this->lifetime}:";
    try {
      $sql = "SELECT COUNT(*) FROM session";
      $sth = $this->dbh->query($sql);
      $count = $sth->fetchColumn();
      if ($count >= MAX_SESSION_COUNT) {
        $this->log .= "**SESSIO LIMIT**";
        $this->gc();
        error_exit(FATAL_SESSION_ERROR . '（セッション数の上限超過）', false, '503 Service Unavailable');
      }
    } catch (PDOException $e) {
      $this->addlog('クエリに失敗しました: ' . $e->getMessage());
      error_exit(FATAL_SESSION_ERROR, false, '500 Internal Server Error');
    }
    return true;
  }

  private function close() {
    $this->dbh = null;
    $this->log .= 'close:';
    return true;
  }

  private function read() {
    $this->log .= "read:{$this->sessionid}:";
    try {
      $sql = "SELECT data, expire FROM session WHERE id='{$this->sessionid}'";
      $sth = $this->dbh->query($sql);
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      if (empty($row)) {
        $this->log .= "**NEW**";
        return '';
      } elseif ($row['expire'] < time()) {
        $this->log .= "**EXPIRE**";
        $this->gc();
        return '';
      } else {
        $this->dict = unserialize(rawurldecode($row['data']));
        return $row['data'];
      }
    } catch (PDOException $e) {
      $this->addlog('クエリに失敗しました: ' . $e->getMessage());
      error_exit(FATAL_SESSION_ERROR, false, '500 Internal Server Error');
    }
  }

  private function write() {
    $this->log .= "write:{$this->sessionid}:{$this->lifetime}:";
    $exp = time() + $this->lifetime;
    try {
      if ($this->alive) {
        $sql = "REPLACE INTO session SET id=?, expire=?, data=?";
        $sth = $this->dbh->prepare($sql);
        $rs = $sth->execute(array($this->sessionid, $exp, null_urlencode(serialize($this->dict))));
      } else {
        $sql = "DELETE FROM session WHERE id=?";
        $sth = $this->dbh->prepare($sql);
        $rs = $sth->execute(array($this->sessionid));  
      }
    } catch (PDOException $e) {
      $this->addlog('クエリに失敗しました: ' . $e->getMessage());
      error_exit(FATAL_SESSION_ERROR . $e->getMessage(), false, '500 Internal Server Error');
    } catch (Exception $e) {
      $this->addlog('その他のエラーが発生しました: ' . $e->getMessage());
      error_exit(FATAL_SESSION_ERROR, false, '500 Internal Server Error');
    }
    return true;
  }

  public function destroy() {
    $this->dict = array();
    $this->alive = false;
    $this->log .= "destroy:";
    return true;
  }

  private function gc() {
    $this->log .= "gc:";
    try {
      $sql = "DELETE FROM session WHERE expire < UNIX_TIMESTAMP()";
      $sth = $this->dbh->prepare($sql);
      $rs = $sth->execute(array(time()));
    } catch (PDOException $e) { 
      $this->addlog('クエリに失敗しました: ' . $e->getMessage());
      error_exit(FATAL_SESSION_ERROR, false, '500 Internal Server Error');
    }
    $this->additional_gc();
    return true;
  }
  protected function additional_gc() {}
}

function null_urlencode($str) {
  return str_replace(array("%", "\0"), array("%25", "%00"), $str);
}