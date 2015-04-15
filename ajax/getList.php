<?php

require_once '../includes/db.php';

$listOfProducts = $mysqli->query("SELECT name, id, commission_pct as perc FROM $TBL_PROD ORDER BY id");

if (!$listOfProducts) {
  header("Status: 500 Database error");
  echo 'Could not retrieve list of products.';
  exit;
}

$products = array();

while ($product = mysqli_fetch_assoc($listOfProducts)) {
  $productId = $product['id'];

  $pricesForProduct = $mysqli->query("SELECT id, low_num_pers as min, high_num_pers as max,"
          . " retail_rate_per_pers as rate FROM $TBL_PRICE WHERE product_id = $productId");

  $prices = array();
  while ($price = mysqli_fetch_assoc($pricesForProduct)) {
    array_push($prices, $price);
  }

  $product['prices'] = $prices;

  array_push($products, $product);
}

echo json_encode($products);
//echo json_encode($results);
