<?php

require_once '../includes/db.php';

$lastId = filter_var($_REQUEST['lastId'], FILTER_SANITIZE_STRING);
$secondLastId = filter_var($_REQUEST['secondLastId'], FILTER_SANITIZE_STRING);
$lastValue = filter_var($_REQUEST['lastValue'], FILTER_SANITIZE_STRING);

$deletessql = "DELETE FROM $TBL_PRICE WHERE id = :lastId";
$deletestatement = $pdo->prepare($deletesql);
$deletestatement->bindParam(':id', $lastId);
if(!$deletestatement->execute()){
      header("Status Code: 503 Server error");
      exit;
    }

// Need to change the value of maxNo in the second last pricing for that product id
$updatesql = "UPDATE $TBL_PRICE SET high_num_pers =:lastValue WHERE id=:secondLastId";
$updatestatement = $pdo->prepare($updatesql);
$updatestatement->bindParam(':lastValue', $lastValue);
$updatestatement->bindParam(':id', $secondLastId);
if(!$updatestatement->execute()){
      header("Status Code: 503 Server error");
      exit;
    }

echo "OK";
?>
