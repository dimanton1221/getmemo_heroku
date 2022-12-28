<?php
require 'class.php';
require "db.php";




$username = $_GET['username'];
// $username = "izzam";

// check from database "SELECT * FROM `user` "

$sql = "SELECT * FROM `user` WHERE `username` = '$username'";


if (status($username) == "on") {
    die(1);
}

$result = $conn->query($sql);
if ($result->num_rows == 0) {
    echo "0";
} else {
    // run roll.php with username in background
    $sql = exec("php -f roll.php '$username' > /dev/null &");
    on($username);
    echo 1;
    // close connection to db
    $conn->close();
}
