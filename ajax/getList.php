<?php

require_once '../includes/db.php';
if (is_array($dbInfo) && array_key_exists(3, $dbInfo)) {
$charset = "utf8";
$dsn ="mysql:host=$DB_HOST;dbname=$DB_NAME;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
}
try{
$pdo = new PDO($dsn, $DB_USER, $DB_PASS, $opt);
$sql = "SELECT name, id, commission_pct as perc from $TBL_PROD ORDER by id";
$selectstmt = $pdo->prepare($sql);
$listOfProducts = $selectstmt->execute();
}
    catch(Exception $e){
    echo 'Unable to access.';
}
if (!$listOfProducts) {
  //header("Status: 500 Database error");
  echo 'Could not retrieve list of products.';
  exit;
}

$products = array();

while ($product = $selectstmt->fetch(PDO::FETCH_ASSOC)) {
  $productId = $product['id'];  
  $sql2 ="SELECT id, low_num_pers as min, high_num_pers as max, retail_rate_per_pers as rate FROM $TBL_PRICE WHERE product_id = :productId";
  $stmt2 = $pdo->prepare($sql);
  $stmt2->bindParam(':tbl_price', $TBL_PRICE);
  $stmt2->bindParam(':productId', $productId);
  $prices = array();
  while ($price = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    array_push($prices, $price);
  }

  $product['prices'] = $prices;

  array_push($products, $product);
}

echo json_encode($products);
//echo json_encode($results);
?>
