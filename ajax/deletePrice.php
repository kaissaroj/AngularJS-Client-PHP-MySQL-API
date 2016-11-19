<?php

require_once '../includes/db.php';

$lastId = filter_var($_REQUEST['lastId'], FILTER_SANITIZE_STRING);
$secondLastId = filter_var($_REQUEST['secondLastId'], FILTER_SANITIZE_STRING);
$lastValue = filter_var($_REQUEST['lastValue'], FILTER_SANITIZE_STRING);

$deletessql = "DELETE FROM :tbl_price WHERE id = :lastId";
$deletestatement = $pdo->prepare($deletesql);
$deletestatement->bindParam(':table_price', $TBL_PRICE);
$deletestatement->bindParam(':id', $lastId);
if(!$deletestatement->execute()){
      header("Status Code: 503 Server error");
      exit;
    }

// Need to change the value of maxNo in the second last pricing for that product id
$updatesql = "UPDATE :tbl_price SET high_num_pers =:lastValue WHERE id=:secondLastId";
$updatestatement = $pdo->prepare($updatesql);
$updatestatement->bindParam(':tbl_price', $TBL_PRICE);
$updatestatement->bindParam(':lastValue', $lastValue);
$updatestatement->bindParam(':id', $secondLastId);
if(!$updatestatement->execute()){
      header("Status Code: 503 Server error");
      exit;
    }

echo "OK";
?>
