<?php
      session_start();
      if(!isset($_SESSION['userdata'])){
        header('location:../');
      }
?>
<html>
    <head>
        <title>Online Voting System - Dashboard</title>
    </head>
    <body>
        <button>Back</button>
       
        <button onclick="window.location.href='logout.php'">Logout</button>

        <h1>Online Voting System</h1>
        <hr>
        <div id="Profile"></div>
         <div id="Group"></div>
    </body>
</html>