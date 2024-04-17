<?php

require '../timer/timer.php';

session_start();

if (!isset($_SESSION['username'])) {
   header('location: ../account/login.php');
}

$userName = $_SESSION['username'];

//set database
$db = new database();

$id = $_GET['id'];

//CONNECTION INTO DATABASE
//show data from url
$query = $db->getConnection()->query("SELECT * FROM tb_product WHERE id_product='$id'");
$data = $query->fetch_assoc();

//koneksi ke database tb_account 
$data_user = $db->getConnection()->query("SELECT id_user FROM tb_account WHERE username='$userName'");
$sql = $data_user->fetch_assoc();

//inner join
$query = "SELECT tb_account.username, tb_account.id_user, highest_price_auction.price
FROM tb_account 
INNER JOIN 
    (SELECT * FROM tb_auction ORDER BY price DESC LIMIT 1) AS highest_price_auction 
ON tb_account.id_user = highest_price_auction.id_user";

$result = $db->getConnection()->query($query);
$auction_data = $result->fetch_assoc();

// var_dump($auction_data);
// exit;

if (isset($_POST['add_bid'])) {

   //define variable
   $amount_bid = $_POST['ammount_bid'];
   $id_user = $_POST['id_user'];
   $id_product = $_POST['id_product'];

   //if price higher than amount bid 
   if ($amount_bid <= $data['price']) {
      echo "<script>
          alert('Harga yang diinput dibawah harga standar!');
          window.location.href = '../index.php';
      </script>";
      exit;
   }


   $sql = new auction($amount_bid, $id_user, $id_product);
   $sql->addAuction();
}

//show data if auction bigger than before 

// $sql_auction = $db->getConnection()->query("SELECT * FROM tb_auction ORDER BY price DESC LIMIT 1");
// $auction_data= $sql_auction->fetch_array();

if ($auction_data == null) {
   $auction_data['price'] = 0;
   $auction_data['username'] = "no user";
}

?>

<html>

<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="description" content="">
   <meta name="author" content="">

   <title>LelangIn | Auction</title>

   <!-- Custom fonts for this template-->
   <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
   <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">


   <!-- CSS FILE -->
   <link rel="stylesheet" href="../style/style_auction.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>

   <div class="hero-section">
      <div class="img">
         <img src="../data_image/<?= $data['image']; ?>">
      </div>
      <div class="detail-product">
         <h2>
            <?= $data['name']; ?>
         </h2>
         <hr>
         <div class="auction-data">
            <div class="d-content">
               <h4 class="judul">
                  Harga awal
               </h4>
               <p>
                  <?= $data['price']; ?>
               </p>
            </div>
            <div class="d-content">
               <h4 class="judul">
                  Harga Lelang
               </h4>
               <p>
                  <?= $auction_data['price']; ?>
               </p>
               <p style="color : #fb8500">
                  <?= $auction_data['username']; ?>
               </p>
            </div>
            <div class="d-content">
               <h4 class="judul">
                  Time Left
               </h4>
               <?php
                  if($data['status'] == "open") {
               ?>
               <p id="demo"></p>
               <?php } else if($data['status'] == "expired") { ?>
                  <p><?= $data['status'];?></p>
               <?php } ?>
            </div>
         </div>

         <hr>
         <?php
         if ($data['status'] == "open") {
         ?>
            <div class="auction-data">
               <div class="d-content">
                  <form action="" method="POST">
                     <h4 class="judul">Current Bid</h4>
                     <div class="input-group mb-3">
                        <span class="input-group-text">Rp.</span>
                        <input type="text" class="form-control" name="ammount_bid" placeholder="<?= $data['price'] ?>">
                        <input type="hidden" name="id_product" id="id_product" value="<?= $data['id_product']; ?> ">
                        <input type="hidden" name="id_user" id="id_user" value="<?= $sql['id_user']; ?>">
                        <button type="submit" class="btn btn-outline-primary" name="add_bid">Add Bid</button>
                     </div>
                  </form>
               </div>
            </div>
         <?php  } else if($data['status'] = "expired") { ?>
            <div class="auction-data">
               <div class="d-content">
                  <form action="" method="POST">
                     <h4 class="judul">Current Bid</h4>
                     <div class="input-group mb-3">
                        <span class="input-group-text">Rp.</span>
                        <input type="text" class="form-control" name="ammount_bid" placeholder="<?= $data['price'] ?>" disabled>
                        <input type="hidden" name="id_product" id="id_product" value="<?= $data['id_product']; ?> ">
                        <input type="hidden" name="id_user" id="id_user" value="<?= $sql['id_user']; ?>">
                        <button type="submit" class="btn btn-outline-primary" name="add_bid" disabled>Add Bid</button>
                     </div>
                  </form>
               </div>
            </div>
         <?php } ?>
      </div>
      <hr>
</body>

</html>