<?php

require_once '../includes/db.php';

$lastId = $_REQUEST['lastId'];
$secondLastId = $_REQUEST['secondLastId'];
$lastValue = $_REQUEST['lastValue'];

$query = "DELETE FROM  $TBL_PRICE WHERE id = $lastId";

if (!$mysqli->query($query)) {
  header("Status Code: 503 Server error");
  exit;
}

// Need to change the value of maxNo in the second last pricing for that product id
$query = "UPDATE $TBL_PRICE SET high_num_pers=$lastValue WHERE id=$secondLastId";
if ($mysqli->query($query)) {
  header("Status Code: 503 Server error");
  exit;
}

echo "OK";
