<?php
  $id_keys = $keys;
  $keys[':userid'] = $id;
  if (! $app->is_super()) {
    $sql = "SELECT COUNT(*) FROM todos WHERE owner != :userid AND id IN (" . implode(",", array_keys($id_keys)) . ")";
    $sth = $dbh->prepare($sql);
    $sth->execute($keys);
    if ($sth->fetchColumn() > 0) {
      error_exit('権限がありません');
    }
  }

  $sql = "SELECT real_filename FROM todos WHERE real_filename IS NOT NULL AND id IN (" . implode(",", array_keys($id_keys)) . ")";
  $sth = $dbh->prepare($sql);
  $sth->execute($id_keys);
  foreach ($sth as $row) {
    @unlink("attachment/${row['real_filename']}");
  }
  $sql = "DELETE FROM todos WHERE id IN (" . implode(",", array_keys($id_keys)) . ")";
  $sth = $dbh->prepare($sql);
  $sth->execute($id_keys);
  $result = $sth->rowCount() . '件削除';
