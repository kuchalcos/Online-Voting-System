<?php
// api/login.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// include DB connection (adjust path if you move this file)
include __DIR__ . '/connect.php'; // expects $connect

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method');
}

// get input (trim to avoid whitespace issues)
$mobile = trim($_POST['mobile'] ?? '');
$password = $_POST['password'] ?? '';
$role = intval($_POST['role'] ?? 0); // optional: client can pass role

if (empty($mobile) || empty($password)) {
    echo "<script>alert('Please enter both mobile and password'); window.location='../index.html';</script>";
    exit;
}

// Prepare and fetch user by mobile (mobile should be unique)
$sql = "SELECT id, name, mobile, password, role, status FROM users WHERE mobile = ? LIMIT 1";
$stmt = mysqli_prepare($connect, $sql);
if (!$stmt) {
    // Fail early if prepare fails
    echo "<script>alert('Database error (prepare failed)'); window.location='../index.html';</script>";
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $mobile);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    // no user found
    echo "<script>alert('No user found with this mobile number'); window.location='../index.html';</script>";
    exit;
}

$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// If user exists, check status (optional)
if (isset($user['status']) && intval($user['status']) === 0) {
    // You may treat status=0 as active or inactive depending on your schema.
    // If you mark inactive with 0, uncomment the following lines to block inactive users:
    // echo "<script>alert('Your account is not active.'); window.location='../index.html';</script>";
    // exit;
}

// Password verification:
// - First try password_verify (for hashed passwords).
// - If that fails, fallback to plain text comparison (legacy). If legacy matches, re-hash and update DB.
$stored = $user['password'];
$login_ok = false;

if (password_verify($password, $stored)) {
    $login_ok = true;
} else {
    // fallback: plain text match (legacy accounts). If matches, re-hash and update DB.
    if ($password === $stored) {
        $login_ok = true;
        // rehash with password_hash and update DB
        $newhash = password_hash($password, PASSWORD_DEFAULT);
        $update_stmt = mysqli_prepare($connect, "UPDATE users SET password = ? WHERE id = ?");
        if ($update_stmt) {
            mysqli_stmt_bind_param($update_stmt, "si", $newhash, $user['id']);
            mysqli_stmt_execute($update_stmt);
            mysqli_stmt_close($update_stmt);
            // update local variable so future checks are consistent
            $stored = $newhash;
        }
    }
}

if (!$login_ok) {
    echo "<script>alert('Incorrect password'); window.location='../index.html';</script>";
    exit;
}

// Optional: verify role if you require matching role from the form
// Uncomment if you want to enforce that the role selected on login must match DB role
/*
if ($role && intval($user['role']) !== $role) {
    echo "<script>alert('Selected role does not match account role'); window.location='../index.html';</script>";
    exit;
}
*/

// Set session variables
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_mobile'] = $user['mobile'];
$_SESSION['user_role'] = intval($user['role']);

// Redirect user based on role (adjust paths as needed)
if (intval($user['role']) === 2) {
    // Group role (role = 2)
    echo "<script>window.location='../routes/dashboard.php';</script>";
    exit;
} else {
    // Default: Voter or other roles (role = 1)
    echo "<script>window.location='../routes/dashboard.php';</script>";
    exit;
}

// close connection (not strictly necessary before exit)
mysqli_close($connect);
?>
