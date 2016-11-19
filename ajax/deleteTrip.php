<?php

require_once '../includes/db.php';

$product_id = filter_var($_REQUEST['pid'], FILTER_SANITIZE_STRING);
$deletessql = "DELETE FROM $TBL_PROD WHERE id = :product_id";
$deletestatement = $pdo->prepare($deletesql);
$deletestatement->bindParam(':product_id', $product_id);
if(!$deletestatement->execute()){
      header("Status Code: 503 Server error");
      exit;
    }

//Also delete the associated prices
$deletessql2 = "DELETE FROM $TBL_PRICE WHERE id = :product_id";
$deletestatement2 = $pdo->prepare($deletesql);
$deletestatement2->bindParam(':product_id', $product_id);

if(!$deletestatement2->execute()){
      header("Status Code: 503 Server error");
      exit;
    }

echo "OK";
?>
