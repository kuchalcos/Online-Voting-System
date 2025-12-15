
<?php session_start();
if(!isset($_SESSION['userdata'])){
    header("location: ../");
}

$userdata = $_SESSION['userdata'];
$groupsdata = $_SESSION['groupsdata'];
if($_SESSION['userdata']['status']==0){
    $status='<b style="color:red">Not Voted</b>';
}
else{
    $status='<b style="color:green"> Voted</b>';
}
?>

<html>
    <head>
        <title>Online Voting System - Dashboard</title>
        <link rel="stylesheet" href="../css/stylesheet.css">
    </head>
    <body>
        <style>
            h1{
                background-color: skyblue;
                 border-radius: 25px;
            }
            #backbtn{
                padding: 5px;
                font-size: 15px;
                background-color: #3498db;
                color: white;
                border-radius: 5px;
                float: left;
                margin: 25px;
            }
            #logoutbtn{
                padding: 5px;
                font-size: 15px;
                background-color: #3498db;
                color: white;
                border-radius: 5px;
                float: right;
                margin: 25px;
            }

            #Profile{
                background-color: white;
                width: 20%;
                padding: 20px;
                float: left;
            }

            #Group{
                background-color: white;
                width: 60%;
                padding: 20px;
                float: right;
            }

            .votebtn{
                padding: 5px;
                font-size: 15px;
                background-color: #3498db;
                color: white;
                border-radius: 5px;
            }

            #mainPanal{
                padding: 10px;
            }
            
        

        </style>

        <div id="mainSection">
            <center>
            <div id="headerSection">
                 <button id="backbtn">Back</button>
                 <button id="logoutbtn" onclick="window.location.href='logout.php'">Logout</button>
                 <h1>Online Voting System</h1>
            </div>
             </center>
                <hr>

                <div id="mainPanal">
<div id="Profile">
   <center><img src="/Online Voting System/api/upload/<?php echo $userdata['photo']?>" height="100" width="100"><br><br></center>
   <b>Name:</b> <?php echo $userdata['name']; ?><br><br>
    <b>Mobile:</b> <?php echo $userdata['mobile']; ?><br><br>
    <b>Address:</b> <?php echo $userdata['address'] ?? 'N/A'; ?><br><br>
    <b>Status:</b> <?php echo $status ?><br><br>
</div>
                <div id="Group">
    <?php
    if (!empty($_SESSION['groupsdata'])) {
        
        $groupsdata = $_SESSION['groupsdata'];

        for ($i = 0; $i < count($groupsdata); $i++) {
    ?>
            <div>
                <img style="float: right;" src="/Online Voting System/api/upload/<?php echo $groupsdata[$i]['photo'] ?>" height="100" width="100">
                <b>Group Name:</b> <?php echo $groupsdata[$i]["name"] ?><br><br>
                <b>Votes:</b> <?php echo $groupsdata[$i]["votes"] ?><br><br>
                
            <form action="../api/vote.php" method="POST">
    <input type="hidden" name="gvotes" value="<?php echo $groupsdata[$i]['votes'] ?>">
    <input type="hidden" name="gid" value="<?php echo $groupsdata[$i]['id'] ?>">
    
    <?php 

    if($userdata['status'] == 0){
       
        ?>
        <input type="submit" name="votebtn" value="Vote" class="votebtn">
        <?php
    } else {
  
        ?>
        <button disabled type="button" class="votebtn" style="background-color: green;">Voted</button>
        <?php
    }
    ?>
    </form>
            </div>
            <hr>
    <?php
        }
    } else {
    
        echo "<div style='padding:10px;'><b>No groups found.</b></div>";
    }
    ?>
</div>
        </div>
                </div>
                
       
        
    </body>
</html>