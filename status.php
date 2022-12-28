<?php
require 'class.php';
require "db.php";

$username = $_GET['username'];
// $username = "izzam";

// check from database "SELECT * FROM `user` "

// if username exist
$sql = "SELECT * FROM `user` WHERE `username` = '$username'";

// run sql 
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    echo 0;
} else {
    $row = $result->fetch_assoc();
    // get token 
    $status = $row['status'];
    // echo $status;
    // make encode status to json
    $array = ['status' => $status];
    $json = json_encode($array);
    echo $json;
    // close connection to db
    $conn->close();
}
