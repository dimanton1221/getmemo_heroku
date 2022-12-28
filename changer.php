<?php
require 'db.php';

// get username
$username = $_GET['username'];

// get settingan
$p = $_GET['p'];
$settingan = $_GET['value'];
$result = $conn->query("SELECT * FROM `user` WHERE `username` = '$username'");
// jika ada
if ($result->num_rows > 0) {
    $result = $conn->query("UPDATE `user` SET `$p` = '$settingan' WHERE `username` = '$username'");
    echo 1;
} else {
    // echo nol
    echo 0;
}
