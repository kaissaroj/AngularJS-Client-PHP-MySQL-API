<?php

require_once '../includes/db.php';

if (isset($_REQUEST['trip']) && isset($_GET['perc'])) {

  $name = $_REQUEST['trip'];
  $perc = $_REQUEST['perc'];

  if (isset($_REQUEST['id'])) {
    $updateId = $_REQUEST['id'];
    $query = "UPDATE $TBL_PROD SET name='$name', commission_pct=$perc WHERE id=$updateId";
//    echo $query;
  } else {
    $query = "INSERT INTO $TBL_PROD(name, commission_pct) VALUES ('$name', $perc)";
  }
  if (!$mysqli->query($query)) {
    header("Status: 500 Database error");
    echo 'Cannot connect to database ' . $mysqli->error;
    exit;
  }

  // Yayy!!
  echo mysqli_insert_id($mysqli);
} else {
  header("Status: 400 Incomplete data");
  echo "Not all fields are filled.";
}
