<?php

//connect into function.php
require_once 'function.php';

//start the session
session_start();

//declare session
$username = $_SESSION['username'];

//check if username doesn't have value
if(!isset($username)){
   //direct into login page
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <title>LelangIn | Account</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- bootstrap  -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

   <!-- CSS -->
   <link rel="stylesheet" href="../style/style_.css">


</head>
<body>

<h1 class="title"> <span>user</span> profile page </h1>

<section class="profile-container">

<?php
   //declare the database 
   $db = new database();

   $select_profile = $db->getConnection()->prepare("SELECT * FROM `tb_account` WHERE username = ?");
   $select_profile->bind_param("s", $username);
   $select_profile->execute();
   $result = $select_profile->get_result();

   $result = $result->fetch_assoc();

   // Check if the query returned any results
   if ($result) {
   ?>
      <div class="profile">
         <img src="../img-profile/<?= $result['image']; ?>" alt="">
         <h3><?= $result['username']; ?></h3>
         <a href="update_profile.php" class="btn btn-primary-outline" name="update-profile">update profile</a>
         <a href="../order.php" class="btn btn-primary-outline">My Order</a>
         <a href="../users/index.php" class="btn">view shop</a>
         <a href="logout.php" class="btn btn-danger-outline">logout</a>
      </div>
   <?php
   } else {
      echo "No profile found for user with ID: $username";
   }

   // Close the prepared statement
   $select_profile->close();
   ?>


</section>
</body>
</html>