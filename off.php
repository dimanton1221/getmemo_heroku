<?php
require 'class.php';
require "db.php";


$username = $_GET['username'];

// $username = "izzam";

// mematikan roll.php
$sql = exec("kill -9 $(pgrep -d' ' -f $username)");
// ganti status jadi off
off($username);