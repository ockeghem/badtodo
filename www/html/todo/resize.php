<?php
  $path = $_GET['path'];
  $basename = $_GET['basename'];
  $ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
  $file = "$path/$basename";
  $size = 0;
  if (isset($_GET['size'])) {
    $size = $_GET['size'];
    $xfile = "$path/_${size}_$basename";
    if (! file_exists($xfile)) {
       copy($file, $xfile);
       // 当初ImageMagicを使っていたがあまりにサイズが大きいのでimgpに変更
       exec("imgp -x {$size}x{$size} -w {$xfile}");
    }
  } else {
    $xfile = $file;
  }
  
  $content_types = array('png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg');
  header("Content-Type: " . $content_types[$ext]);
  header("Content-Length: " . @filesize($xfile));
  @readfile($xfile);
