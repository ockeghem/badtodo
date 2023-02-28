<?php
  $sql = "UPDATE todos SET done=1 WHERE id IN (" . implode(",", array_keys($keys)) . ")";
  $sth = $dbh->prepare($sql);
  $sth->execute($keys);
  $result = $sth->rowCount() . '件完了に';
