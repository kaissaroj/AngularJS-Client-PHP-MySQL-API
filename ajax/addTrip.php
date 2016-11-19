<?php

require_once '../includes/db.php';

if (isset($_REQUEST['trip']) && isset($_GET['perc'])) {
  $name = filter_var($_REQUEST['trip'], FILTER_SANITIZE_STRING);
  $perc = filter_var($_REQUEST['perc'], FILTER_SANITIZE_STRING);

  if (isset($_REQUEST['id'])) {
    $updateId = filter_var($_REQUEST['id'], FILTER_SANITIZE_STRING);
    $updatesql = "UPDATE :table_prod SET name= :name, commission_pct :perc WHERE id= :updateId";
    $updatestatement = $pdo->prepare($updatesql);
    $updatestatement->bindParam(':table_prod', $_TBL_PROD);
    $updatestatement->bindParam(':name', $name);
    $updatestatement->bindParam(':commission_pct', $perc);
    $updatestatement->bindParam(':id', $updateId);
    if(!$updatestatement->execute()){
      header("Status: 500");
      echo 'Cannot update entry due to database error.' . print_r($pdo->errorInfo());
      exit;
    }
    // Yayy!
    echo $price_id;
  } else {
    $insertsql = "INSERT INTO :tbl_prod(name, comission_pct) VALUES(:name, :perc)";
    $insertstatement = $pdo->prepare($insertsql);
    $insertstatement->bindParam(':tbl_prod', $TBL_PROD);
    $insertstatement->bindParam(':name', $name);
    $insertstatement->bindParam(':perc', $perc);
    if (!$insertstmt->execute()) {
          header("Status: 500 Database error");
          echo 'Cannot insert new item due to database errors.' . print_r($pdo->errorInfo());
          exit;
        }
    // Cool!
    echo $pdo->lastInsertId();
  }
} else {
  header("Status: 400 Incomplete data");
  echo "Not all fields are filled.";
}
