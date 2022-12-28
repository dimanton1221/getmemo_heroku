<?php
// req class.php and db
require 'class.php';
require "db.php";

$username = $_GET['username'];
// $username = "izzam";

// SELECT * FROM `logs`
// ambil logs dengan nama dan delete logs
$sql = "SELECT * FROM `logs` WHERE `username` = '$username' ";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    echo "0";
} else {
    // show all db to json

    while ($row = $result->fetch_assoc()) {
        // hilangkan id dari row
        unset($row['id']);
        unset($row['username']);
        $array[] = $row;
    }
    $json = json_encode($array);
    echo $json;
    // save it to file logs.json
    // logs to sdtderr
    // delete it
    $sql = "DELETE FROM `logs` WHERE `username` = '$username'";
    $conn->query($sql);
    // close connection to db
    $conn->close();
}
