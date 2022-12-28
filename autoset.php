<?php
require 'db.php';


// show SELECT * FROM `autoset` and  close connection
$result = $conn->query("SELECT * FROM `autoset`");
// simpan dalam var terus di echo dalam json
$array= array();
while($row = $result->fetch_assoc()) {
    // hanya ambil yang name_settings
    $array[] = $row['name_settings'];
}
echo json_encode($array);
?>