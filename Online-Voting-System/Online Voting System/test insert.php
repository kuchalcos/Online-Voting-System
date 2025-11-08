<?php
// test_insert.php — quick DB insert test
include __DIR__.'/api/connect.php'; // adjust path if needed

// show errors while debugging
ini_set('display_errors',1);
error_reporting(E_ALL);

if (!$connect) {
    die('No DB connection: '. mysqli_connect_error());
}

$sql = "INSERT INTO users (name, mobile, password, address, photo, role, status, votes)
        VALUES ('Test User', '9800000000', 'testpass', 'Test Address', 'none.jpg', 1, 0, 0)";

$res = mysqli_query($connect, $sql);
if ($res) {
    echo "INSERT OK. Last id: " . mysqli_insert_id($connect);
} else {
    echo "INSERT FAILED: " . mysqli_error($connect);
}
?>
