<?php

require 'connection.php';

?>

<!DOCTYPE html>
<html>

<head>

   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="description" content="">
   <meta name="author" content="">

   <title>LelangIn dulu</title>

   <!-- Custom fonts for this template-->
   <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
   <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">


   <!-- CSS FILE -->
   <link rel="stylesheet" href="style/style.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>
   <?php
   session_start(); // Pastikan memulai session di awal skrip
   if (!isset($_SESSION['username'])) { // Jika pengguna belum login
   ?>
      <nav class="navbar">
         <div class="logo">
            <a href="#">LelangIn</a>
         </div>
         <div class="navdiv">
            <ul>
               <li><a href="index.php">Home</a></li>
               <li><a href="about.php">About</a></li>
               <li><a href="cart.php">Cart</a></li>
               <button><a href="account/login.php">LogIn</a></button>
               <button><a href="account/signup.php">SignUp</a></button>
            </ul>
         </div>
      </nav>
   <?php
   } else { // Jika pengguna sudah login
   ?>
      <nav class="navbar">
         <div class="logo">
            <a href="#">LelangIn</a>
         </div>
         <div class="navdiv">
            <ul>
               <li><a href="index.php">Home</a></li>
               <li><a href="about.php">About</a></li>
               <li><a href="cart.php">Cart</a></li>
               <button><a href="account.php">account</a></button>
            </ul>
         </div>
      </nav>
   <?php
   }
   ?>

   <div class="hero-section">
      <h1>
         Punya barang masih bagus tapi ga kepakai?
         <br>
         LelangIn Aja dulu
      </h1>

      <button>
         <a href="">Get Started</a>
      </button>
   </div>

   <div class="main-section">
      <div class="header">
         <h1>Our Products</h1>
      </div>

      <div class="container">
         <section class="products">
            <div class="row row-cols-1 row-cols-md-2 g-4">
               <?php
               //connect into class database
               $db = new database();

               //query shows data from tb_auction
               $query = $db->getConnection()->query("SELECT * FROM tb_auction ORDER BY price DESC LIMIT 1");
               $auction_data = $query->fetch_assoc();

               //query shows data from tb_product
               $sql = $db->getConnection()->query("SELECT * FROM `tb_product`");
               if (mysqli_num_rows($sql) > 0) {
                  while ($fetch_product = mysqli_fetch_assoc($sql)) {

                     //limit shows character on description
                     $description = $fetch_product["description"];
                     $description = substr($description, 0, 150);

               ?>
                     <div class="col-md-6">
                        <div class="card mb-3">
                           <div class="row g-0">
                              <div class="col-md-4">
                                 <img src="data_image/<?php echo $fetch_product['image']; ?>" class="card-img-top " alt="">
                              </div>
                              <div class="col-md-8">
                                 <div class="card-body">
                                    <h5 class="card-title"><?php echo $fetch_product['name']; ?></h5>

                                    <?php 
                                       if($auction_data['price'] != 0) {
                                    ?>
                                    <p class="card-text">Harga Lelang :  <b><?= $auction_data['price']; ?></b></p>
                                    <?php 
                                       }

                                       if($auction_data['price'] == null) {
                                    ?>
                                       <p class="card-text">Harga awal : <b><?= $fetch_product['price']; ?></b></p>
                                    <?php 
                                       }
                                    ?>
                                    <p class="card-text"><?= $description; ?></p>
                                    <a href="auction/auction.php?id=<?= $fetch_product['id_product']; ?>">
                                    <button type="button" class="btn btn-outline-warning" >
                                       More Details 
                                    </button>
                                 </a>
                                 </div>
                               
                              </div>
                           </div>
                        </div>
                     </div>
               <?php
                  }
               }
               ?>
            </div>
         </section>
      </div>
   </div>

   <div class="footer">

   </div>

</body>

</html>