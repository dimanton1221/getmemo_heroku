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
    echo "Username not found";
} else {
    $row = $result->fetch_assoc();
    // get token 
    $token = $row['token'];
    $api = new para($token);
    $DATAS = $api->getBalanceDash();
    echo $DATAS;
}
