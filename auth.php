<?php
require "db.php";
require "class.php";

// get username
$token = $_GET['token'];
$password = $_GET['password'];
$idAplikasi = $_GET['idAplikasi'];

$para = new para($token);
$para->getMe();
$username = $para->username;

// jika INSERT INTO `user_master` (`id`, `username`, `id_aplikasi`) VALUES (NULL, '', '1');
$sql = "SELECT * FROM `user_master` WHERE `id_aplikasi` = '$idAplikasi'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // logs()
    heroku_terminal("sudah ada user master");
    $sql = "SELECT * FROM `user` WHERE `username` = '$username'";
    $hasil = $conn->query($sql);
    if ($hasil->num_rows == 0) {
        // buat user baru dengan contoh seperti ini "INSERT INTO `user` (`id`, `username`, `password`, `token`, `status`, `settingan`, `profit_global`, `shot`, `shot_status`, `id_aplikasi`) VALUES (NULL, 'dsds', 'dsds', 'dsds', 'off', '', '1', '1', 'off', '');"
        $sql = "INSERT INTO `user` (`id`, `username`, `password`, `token`, `status`, `settingan`, `profit_global`, `shot`, `shot_status`, `id_aplikasi`) VALUES (NULL, '$username', '$password', '$token', 'off', '', '1', '1', 'off', '$idAplikasi');";
        $conn->query($sql);
        die("$username");
    } else {
        // update id_aplikasi dan password
        $sql = "UPDATE `user` SET `id_aplikasi` = '$idAplikasi', `password` = '$password' WHERE `user`.`username` = '$username'";
        $conn->query($sql);
        die("$username");
    }
} else {
    die("0");
}
