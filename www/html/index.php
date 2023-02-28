<?php
  if ($_SERVER['SERVER_PORT'] != 80) {
    header('Location: set_proxy.php');
    exit;
  }
  header('Location: /todo/');
  echo $_SERVER['SERVER_PORT'];

