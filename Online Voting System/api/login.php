<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include __DIR__ . '/connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method');
}


$mobile = trim($_POST['mobile'] ?? '');
$password = $_POST['password'] ?? '';
$role = intval($_POST['role'] ?? 0); 

if (empty($mobile) || empty($password)) {
    echo "<script>alert('Please enter both mobile and password'); window.location='../index.html';</script>";
    exit;
}

$sql = "SELECT id, name, mobile, password,address, role, status, photo FROM users WHERE mobile = ? LIMIT 1";
$stmt = mysqli_prepare($connect, $sql);
if (!$stmt) {
    
    echo "<script>alert('Database error (prepare failed)'); window.location='../index.html';</script>";
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $mobile);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    
    echo "<script>alert('No user found with this mobile number'); window.location='../index.html';</script>";
    exit;
}

$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (isset($user['status']) && intval($user['status']) === 0) {
   
}

$stored = $user['password'];
$login_ok = false;

if (password_verify($password, $stored)) {
    $login_ok = true;
} else {
   
    if ($password === $stored) {
        $login_ok = true;
        
        $newhash = password_hash($password, PASSWORD_DEFAULT);
        $update_stmt = mysqli_prepare($connect, "UPDATE users SET password = ? WHERE id = ?");
        if ($update_stmt) {
            mysqli_stmt_bind_param($update_stmt, "si", $newhash, $user['id']);
            mysqli_stmt_execute($update_stmt);
            mysqli_stmt_close($update_stmt);
            $stored = $newhash;
        }
    }
}

if (!$login_ok) {
    echo "<script>alert('Incorrect password'); window.location='../index.html';</script>";
    exit;
}


$_SESSION['userdata'] = $user;

$groups = mysqli_query($connect, "SELECT * FROM users WHERE role=2");
$groupsdata = mysqli_fetch_all($groups, MYSQLI_ASSOC);
$_SESSION['groupsdata'] = $groupsdata;


if (intval($user['role']) === 2) {
    echo "<script>window.location='../routes/dashboard.php';</script>";
    exit;
} else {
    echo "<script>window.location='../routes/dashboard.php';</script>";
    exit;
}

mysqli_close($connect);
?>
