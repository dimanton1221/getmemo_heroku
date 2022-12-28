<?php
require "class.php";
require "db.php";

// get username
$username = $_GET['user'];

if (isset($username) && !empty($username)) {

    // 
    // get token from db
    $token = $conn->query("SELECT token FROM user WHERE username = '$username'");
    $token = $token->fetch_assoc();
    $token = $token['token'];
    $para = new para($token);
    // get wallet dash
    $wallet = $para->getDepositAddressDash();
    // echo wallet use json
    echo json_encode(['wallet' => $wallet]);
}
