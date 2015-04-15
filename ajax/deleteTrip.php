<?php

require_once '../includes/db.php';

$product_id = $_REQUEST['pid'];
$query = "DELETE FROM $TBL_PROD WHERE id=$product_id";

if (!$mysqli->query($query)) {
  header("Status Code: 503 Server error");
  exit;
}

//Also delete the associated prices
$query = "DELETE FROM $TBL_PRICE WHERE product_id=$product_id";

if (!$mysqli->query($query)) {
  header("Status Code: 503 Server error");
  exit;
}

echo "OK";
