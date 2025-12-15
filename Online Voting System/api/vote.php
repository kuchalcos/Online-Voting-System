<?php
session_start();
include('connect.php');

if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['status'] == 1) {
    echo '
    <script>
    alert("You have already voted!");
    window.location="../routes/dashboard.php";
    </script> ';
    exit(); 
}

$gid = $_POST['gid'];
$uid = $_SESSION['userdata']['id'];


$update_votes = mysqli_query($connect, "UPDATE users SET votes = votes + 1 WHERE id='$gid'");

$update_user_status = mysqli_query($connect, "UPDATE users SET status=1 WHERE id='$uid'");



if ($update_votes AND $update_user_status) {
  
    $groups = mysqli_query($connect, "SELECT * FROM users WHERE role=2");
    $groupsdata = mysqli_fetch_all($groups, MYSQLI_ASSOC);

   
    $userdata_query = mysqli_query($connect, "SELECT * FROM users WHERE id='$uid'");
    $_SESSION['userdata'] = mysqli_fetch_assoc($userdata_query);

   
    $_SESSION['groupsdata'] = $groupsdata; 
    
    echo '
    <script>
    alert("Voting successful!");
    window.location="../routes/dashboard.php";
    </script> ';
} else {
  
    $error = mysqli_error($connect);
    
    echo '
    <script>
    alert("Voting failed. Database error: ' . $error . '");
    window.location="../routes/dashboard.php";
    </script> ';
}
?>