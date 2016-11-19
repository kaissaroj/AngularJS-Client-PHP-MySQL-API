<?php
require_once '../includes/db.php';
$charset = "utf8";
$dsn ="mysql:host=$DB_HOST;dbname=$DB_NAME;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try{
$pdo = new PDO($dsn, $DB_USER, $DB_PASS, $opt);

if ($_REQUEST['productId'] || $_REQUEST['minNo'] || $_REQUEST['maxNo'] || $_REQUEST['retail']) {
  $low_num_pers = filter_var($_REQUEST['minNo'], FILTER_SANITIZE_STRING);
  $high_num_pers = filter_var($_REQUEST['maxNo'], FILTER_SANITIZE_STRING);
  $retail_rate_per_pers = filter_var($_REQUEST['retail'], FILTER_SANITIZE_STRING);
  $product_id = filter_var($_REQUEST['productId'], FILTER_SANITIZE_STRING);
}
  if (!$high_num_pers) {
    $high_num_pers = 0;
  }

  // (Optional) Is it an update?
  if (isset($_REQUEST['priceId'])) {
    $price_id = filter_var($_REQUEST['priceId'], FILTER_SANITIZE_STRING);
    $updatesql = "UPDATE :table_price SET low_num_pers= :low_num_pers, high_num_pers= :high_num_pers, retail_rate_per_pers=:retail_rate_per_pers, product_id=:product_id WHERE id= :price_id";
    $updatestatement = $pdo->prepare($updatesql);
    $updatestatement->bindParam(':table_price', $_TBL_PRICE);
    $updatestatement->bindParam(':low_num_pers', $low_num_pers);
    $updatestatement->bindParam(':high_num_pers', $high_num_pers);
    $updatestatement->bindParam(':retail_rate_per_pers', $retail_rate_per_pers);
    $updatestatement->bindParam(':product_id', $product_id);
    $updatestatement->bindParam(':price_id', $price_id);
    if(!$updatestatement->execute()){
      header("Status: 500");
      echo 'Cannot update entry due to database error.' . print_r($pdo->errorInfo());
      exit;
    }
    // Yayy!
    echo $price_id;
  }
  // No, this is a new entry
  else {
    $insert = "INSERT INTO tbl_product_price(low_num_pers, high_num_pers, retail_rate_per_pers, product_id) VALUES(:low_num_pers, :high_num_pers, :retail_rate_per_pers, :product_id)";
    $insertstmt = $pdo->prepare($insert);
    $insertstmt->bindParam(':low_num_pers', $low_num_pers);
    $insertstmt->bindParam(':high_num_pers', $high_num_pers);
    $insertstmt->bindParam(':retail_rate_per_pers', $retail_rate_per_pers);
    $insertstmt->bindParam(':product_id', $product_id);
    if (!$insertstmt->execute()) {
      header("Status: 500 Database error");
      echo 'Cannot insert new item due to database errors.' . print_r($pdo->errorInfo());
      exit;
    }
    // Cool!
    echo $pdo->lastInsertId();
  }
}     catch(Exception $e){
    header("Status: 500");
    echo 'Cannot insert new item due to database errors.'. $e->getMessage();
     
}
?>
