<?php
require 'db.php';

// get username
$username = $_GET['username'];

// get settingan
$settingan = $_GET['settingan'];

$result = $conn->query("SELECT * FROM `user` WHERE `username` = '$username'");
// jika ada
if ($result->num_rows > 0) {
    $result = $conn->query("UPDATE `user` SET `settingan` = '$settingan' WHERE `username` = '$username'");
    echo 1;
} else {
    // echo nol
    echo 0;
}
