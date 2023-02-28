<?php
require_once 'session.php';

class BadApp extends DbSessionManager {
  protected $logger;
  private $user;
  private $islogin = -999;

  public function __construct() {
    $this->logger = new Logger('todolog');
    parent::__construct();
  }

  public function check_login() {
    $user = $this->get('user');
    if (! empty($user)) {
      $this->user = $user;
      $this->islogin = true;
    } elseif (! empty($_COOKIE['AUTOLOGIN'])) {
      $user = unserialize($_COOKIE['AUTOLOGIN']);
      $this->set_user($user, true);
    } else {
      $user = $this->new_user();
    }
    return $user;
  }

  public function set_user($user, $fillsession = false) {
    $this->user = $user;
    $id = $user->get_id();
    $this->islogin = ! empty($id);
    if ($fillsession) {
      $this->set('user', $user);
      $this->send_session_cookie();
    }
  }

  public function new_user($id = false, $userid = false, $super = 0, $fillsession = false) {
    $this->log .= "new_userid:";
    $this->user = new User($id, $userid, $super);
    if ($fillsession) {
      $this->log .= "snew_fillsession:";
      $this->set('user', $this->user);
      $this->send_session_cookie();
    }
    $this->islogin = $id !== false;
    return $this->user; 
  }

  public function is_loggedin() {
    return $this->islogin;
  }
  public function get_id() {
    return $this->user->get_id();
  }
  public function get_userid() {
    return $this->user->get_userid();
  }
  public function is_super() {
    return $this->user->is_super();
  }
  public function require_loggedin() {
    if (! $this->is_loggedin()) {
      $user = $this->new_user();
      $app = $this;
      $current = $_SERVER['SCRIPT_NAME'];
      $title = "ログアウトしています";
      $content = 'この操作にはログインが必要です <a href="login.php?url=' . h($current) . '">ログイン</a>';
      require "template.php";
      exit;
    }
    return $this->user->get_id();
  }
  public function destroy() {
    parent::destroy();
    $user = $this->new_user();
    $islogin = false;
  
  }
  public function get_token() {
    $token = $this->get(TOKENNAME);
    if (empty($token)) {
      $token = bin2hex(openssl_random_pseudo_bytes(TOKENLEN));
      $this->set(TOKENNAME, $token);
    }
    return $token;
  }
  public function require_token($post_token = null) {
    if (empty($post_token))
      $post_token = filter_input(INPUT_POST, TOKENNAME);
    $session_token = $this->get(TOKENNAME);
    if (empty($session_token) || $session_token !== $post_token) {
      error_exit('正規の画面から使用下さい', false, '403 Forbidden');
    }
  }
  public function requested_id($method = INPUT_GET) {
    $id = $this->user->get_id();
    $reqid = filter_input($method, 'id');
    if (empty($reqid)) {
      $reqid = $id;
    } elseif (! $this->is_super() && $id !== $reqid) {
      error_exit('権限がないか、そのユーザは存在しません');
    }
    return $reqid;
  }
  public function a($url, $text, $random = true, $params = array(), $attrib = array()) {
    if ($random)
      $params = array_merge(array('rnd' => uniqid()), $params);
     if (! $this->cookie_avail) {
      $params = array_merge(array(SESSIDNAME => $this->sessionid), $params);
    }
    $result =  '<a href="' . $url;
    if (! empty($params)) {
      $r = array();
      foreach ($params as $key => $value) {
        $r[] = "$key=$value";
      }
      $result .= "?" . implode("&", $r);
    }
    $result .= '"';
    if (! empty($attrib)) {
      $r = array();
      foreach ($attrib as $key => $value) {
        $r[] = "$key=\"$value\"";
      }
      $result .= " " . implode("&", $r);
    }
    $result .= ">$text</a>";
    echo $result;
  }

  public function form($url, $post_method = true, $params = array(), $attrib = array(), $csrftoken = false) {
    if (! $post_method)
      $params = array_merge(array('rnd' => uniqid()), $params);
    if (! $this->cookie_avail) {
      $params = array_merge(array(SESSIDNAME => $this->sessionid), $params);
    }
    if ($csrftoken) {
      $params = array_merge(array(TOKENNAME=> $this->get_token()), $params);
    }
    $result =  "<form action='$url' method='" . ($post_method ? 'post' : 'get') . "'";
    if (! empty($attrib)) {
      $r = array();
      foreach ($attrib as $key => $value) {
        $r[] = "$key=\"$value\"";
      }
      $result .= " " . implode("&", $r);
    }
    $result .= ">";
    if (! empty($params)) {
      $r = array();
      foreach ($params as $key => $value) {
        $r[] = "<input type='hidden' name='$key' value='$value'>";
      }
      $result .= "\n" . implode("\n", $r);
    }
    echo $result . "\n";
  }

  public function addlog($log) {
    $this->logger->add($log);
  }
  protected function additional_gc() {
    foreach (glob('temp/*') as $i => $file) {
      if (filemtime($file) < time() - 3600) {  // 1時間以上前のファイルを削除
        unlink($file);
      }
    }
  }
} // End BadSession
