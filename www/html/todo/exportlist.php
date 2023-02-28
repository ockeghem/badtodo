<?php
  $sql = "todos.id IN (" . implode(",", array_keys($keys)) . ")";
  $queryarray = array('sql' => $sql, 'keys' => $keys);
  $query = rawurlencode((var_export($queryarray, true)));
  header("Location: exportdo.php?query=$query");
  exit;
