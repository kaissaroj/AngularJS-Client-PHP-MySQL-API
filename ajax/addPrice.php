<?php

require_once '../includes/db.php';

if ($_REQUEST['productId'] || $_REQUEST['minNo'] || $_REQUEST['maxNo'] || $_REQUEST['retail']) {

  $low_num_pers = $_REQUEST['minNo'];
  $high_num_pers = $_REQUEST['maxNo'];
  $retail_rate_per_pers = $_REQUEST['retail'];
  $product_id = $_REQUEST['productId'];

  if (!$high_num_pers) {
    $high_num_pers = 0;
  }



  // (Optional) Is it an update?
  if (isset($_REQUEST['priceId'])) {
    $price_id = $_REQUEST['priceId'];

    $query = "UPDATE $TBL_PRICE SET low_num_pers = $low_num_pers,"
            . " high_num_pers = $high_num_pers, retail_rate_per_pers = $retail_rate_per_pers"
            . " ,product_id = $product_id WHERE id = $price_id";

    if (!$mysqli->query($query)) {
      header("Status: 500");
      echo 'Cannot update entry due to database error.' . $mysqli->error;
      exit;
    }
    // Yayy!
    echo $price_id;
  }
  // No, this is a new entry
  else {
    $query = "INSERT INTO tbl_product_price(low_num_pers,high_num_pers,retail_rate_per_pers,product_id)"
            . " VALUES ('$low_num_pers','$high_num_pers','$retail_rate_per_pers','$product_id')";

    if (!$mysqli->query($query)) {
      header("Status: 500 Database error");
      echo 'Cannot insert new item due to database errors.' . $mysqli->error;
      exit;
    }
    // Cool!
    echo mysqli_insert_id($mysqli);
  }
} else {
  header("Status: 500");
}