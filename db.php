<?php
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));


$host = "localhost";
$username = "root";
$password = "";
$dbname = "aplikasiku";
$port = "3306";




// jika tersedia heroku db
if (isset($url["host"])) {
    $host = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $dbname = substr($url["path"], 1);
    // jika ada port
    if (isset($url["port"])) {
        $port = $url["port"];
    }
}
$conn = new mysqli($host, $username, $password, $dbname, $port);



if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function logs($username, $text)
{
    // ambil global conn
    global $conn;
    // add text to table logs
    $sql = "INSERT INTO `logs` (`username`, `text`) VALUES ('$username', '$text')";
    $conn->query($sql);
}


// buat fungsi ganti status off
function off($username)
{
    global $conn;
    $sql = "UPDATE `user` SET `status` = 'off' WHERE `username` = '$username'";
    $conn->query($sql);
}
function on($username)
{
    global $conn;
    $sql = "UPDATE `user` SET `status` = 'on' WHERE `username` = '$username'";
    $conn->query($sql);
}
function heroku_terminal($text)
{
    file_put_contents("php://stderr", $text . "\n");
}
function status($username)
{
    global $conn;
    $sql = "SELECT * FROM `user` WHERE `username` = '$username'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['status'];
    // close
    $conn->close();
}


function off_shot($username)
{
    global $conn;
    $sql = "UPDATE `user` SET `shot_status` = 'off' WHERE `username` = '$username'";
    $conn->query($sql);
}


function shot_status($username)
{
    global $conn;
    $sql = "SELECT * FROM `user` WHERE `username` = '$username'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['shot_status'];
    // close
    // $conn->close();
}
// echo shot_status("izzam");
