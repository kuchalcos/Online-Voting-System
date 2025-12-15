<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include __DIR__ . "/connect.php"; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method');
}

$name     = trim($_POST['name'] ?? '');
$mobile   = trim($_POST['mobile'] ?? '');
$password = $_POST['password'] ?? '';
$cpassword= $_POST['cpassword'] ?? '';
$address  = trim($_POST['address'] ?? '');
$role     = intval($_POST['role'] ?? 1);
if ($password !== $cpassword) {
    echo "<script>alert('Password and Confirm Password do not match'); window.location='../routes/register.html';</script>";
    exit;
}

if (empty($name) || empty($mobile) || empty($password)) {
    echo "<script>alert('Please fill required fields'); window.location='../routes/register.html';</script>";
    exit;
}

if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    echo "<script>alert('Please upload a photo'); window.location='../routes/register.html';</script>";
    exit;
}

$uploadDir = __DIR__ . "/upload";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true); 
}

$originalName = basename($_FILES['photo']['name']);
$tmpName = $_FILES['photo']['tmp_name'];
$ext = pathinfo($originalName, PATHINFO_EXTENSION);

try {
    $uniqueSuffix = bin2hex(random_bytes(6));
} catch (Exception $e) {
    $uniqueSuffix = uniqid();
}
$uniqueName = time() . '_' . $uniqueSuffix . '.' . $ext;
$destination = $uploadDir . '/' . $uniqueName;

if (!move_uploaded_file($tmpName, $destination)) {
    echo "<script>alert('Failed to save uploaded file'); window.location='../routes/register.html';</script>";
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (name, mobile, password, address, photo, role, status, votes) VALUES (?, ?, ?, ?, ?, ?, 0, 0)";
$stmt = mysqli_prepare($connect, $sql);
if (!$stmt) {
    die("Prepare failed: " . mysqli_error($connect));
}

mysqli_stmt_bind_param($stmt, "sssssi", $name, $mobile, $hashed, $address, $uniqueName, $role);

$executed = mysqli_stmt_execute($stmt);
if ($executed) {
    echo "<script>alert('Registration Successful'); window.location='../index.html';</script>";
} else {
    $err = mysqli_stmt_error($stmt);
    echo "<script>alert('Registration Failed: " . addslashes($err) . "'); window.location='../routes/register.html';</script>";
}

mysqli_stmt_close($stmt);
mysqli_close($connect);
?>

