<?php
$connect = mysqli_connect("localhost", "root", "", "voting");
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
// do not echo anything here in production
?>

