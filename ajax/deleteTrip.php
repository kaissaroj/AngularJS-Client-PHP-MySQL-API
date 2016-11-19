<?php

require_once '../includes/db.php';

$product_id = filter_var($_REQUEST['pid'], FILTER_SANITIZE_STRING);
$deletessql = "DELETE FROM :tbl_prod WHERE id = :product_id";
$deletestatement = $pdo->prepare($deletesql);
$deletestatement->bindParam(':tbl_prod', $TBL_PROD);
$deletestatement->bindParam(':product_id', $product_id);
if(!$deletestatement->execute()){
      header("Status Code: 503 Server error");
      exit;
    }

//Also delete the associated prices
$deletessql2 = "DELETE FROM :tbl_price WHERE id = :product_id";
$deletestatement2 = $pdo->prepare($deletesql);
$deletestatement2->bindParam(':tbl_price', $TBL_PRICE);
$deletestatement2->bindParam(':product_id', $product_id);

if(!$deletestatement2->execute()){
      header("Status Code: 503 Server error");
      exit;
    }

echo "OK";
?>
