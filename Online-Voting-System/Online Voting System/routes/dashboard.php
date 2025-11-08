<?php
      session_start();
      if(!isset($_SESSION['userdata'])){
        header('location:../');
      }
      $userdata = $_SESSION['userdata'];
       $userdata = $_SESSION['groupsdata'];
?>
<html>
    <head>
        <title>Online Voting System - Dashboard</title>
         <link rel="stylesheet" href="../css/stylesheet.css">
    </head>
    <body>
        <style>


            button{
                margin:10px;
                padding:10px;
                font-size:16px;
                cursor:pointer;
                float:left;
            }
#profile{
    background-color:white;
    width: 30%;
    padding:20px;
    float:left;
}

#Group{
    background-color:white;
    width: 60%;
    padding:20px;
    float:right;
}

        </style>
<div id="mainsection">
    <center>
<button>Back</button>
       
        <button onclick="window.location.href='logout.php'">Logout</button>

        <h1>Online Voting System</h1>
        </center>
        <hr>
        
        <div id="Profile">
         <center><img src="../upload/<?php echo $userdata['photo'] ?>" height="100px" width="100px"></center><br><br>
            <b>Name:</b><?php echo $userdata['name'] ?><br><br>
             <b>Mobile:</b><?php echo $userdata['mobile'] ?><br><br>
              <b>Address:</b><?php echo $userdata['address'] ?><br><br>
               <b>Status:</b><?php echo $userdata['status'] ?><br><br>
        </div>
         <div id="Group">
            <?php
            if($_SESSION['groupsdata']){
for ($i=0; $i < count($groupsdata); $i++) {
    ?>
    <div>
        
        <b>Group Name:</b>
         <b>Votes:</b>
         <form action="#">
            <input type="hidden" name="gvotes" value="">
            <input type="submit" name="votebtn" value="Vote" id="votebtn">
            </form>
    </div>
    <?php
}
            }
            else{

            }
            ?>
         </div>
</div>

        
    </body>
</html>