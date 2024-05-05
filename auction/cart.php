<?php
require_once 'function.php';
session_start();

$db = new database();

// Catch username from session
$username = $_SESSION['username'] ?? '';

// INNER JOIN tb_product & tb_cart
$query_cart = "SELECT tb_product.id_product, tb_product.name, tb_product.status, tb_product.price, tb_product.image, tb_product.quantity, tb_cart.id_product FROM tb_product INNER JOIN tb_cart ON tb_product.id_product = tb_cart.id_product";
$query_cart = $db->getConnection()->query($query_cart);

// Initialize variables to store cart details
$product_id = $product_name = $product_image = $product_quantity = $product_price = null;

if ($query_cart->num_rows > 0) {
   while ($data_product = $query_cart->fetch_assoc()) {
      $product_id = $data_product['id_product'];
      $product_name = $data_product['name'];
      $product_image = $data_product['image'];
      $product_quantity = $data_product['quantity'];
      $product_price = $data_product['price'];
      $product_status = $data_product['status'];
   }
}

// INNER JOIN tb_account & tb_address
$query_address = "SELECT `tb_address`.`desa`, `tb_address`.`kecamatan`, `tb_address`.`kota/kabupaten`, `tb_address`.`provinsi`, `tb_address`.`negara`, `tb_address`.`id_address`, `tb_account`.`id_user` FROM `tb_address` INNER JOIN `tb_account` ON `tb_address`.`id_user` = `tb_account`.`id_user`";
$query_address = $db->getConnection()->query($query_address);

if ($query_address->num_rows > 0) {
   while ($data = $query_address->fetch_assoc()) {
      $data_user = $data['id_user'];
      $address_id = $data['id_address'];
      $address_desa = $data['desa'];
      $address_kecamatan = $data['kecamatan'];
      $address_kota = $data['kota/kabupaten'];
      $address_provinsi = $data['provinsi'];
      $address_negara = $data['negara'];
   }
}

// get query from tb_auction
$sql_auction = "SELECT * FROM `tb_auction` ORDER BY `price` DESC";
$data_auction = $db->getConnection()->query($sql_auction);
$data_auction = $data_auction->fetch_assoc();

//get query from tb_product
$sql_product = "SELECT * FROM `tb_product` ORDER BY `status` DESC";
$data_product = $db->getConnection()->query($sql_product);
$data_product = $data_product->fetch_assoc();

// Query to fetch user details
$query_profile = $db->getConnection()->prepare("SELECT * FROM tb_account WHERE username = ?");
$query_profile->bind_param("s", $username);
$query_profile->execute();
$result_profile = $query_profile->get_result();

// Check if checkout button clicked
if (isset($_POST['checkout'])) {
   $product_id = $_POST['product_id'];
   $user_id = $_POST['user_id'];
   $address_id = $_POST['address_id'];

   $query = new checkout($product_id, $user_id, $address_id);
   $query->buyProduct();
}

if ($result_profile->num_rows > 0) {
   $data_profile = $result_profile->fetch_assoc();
   $id_user = $data_profile['id_user'];

   // Find the overall highest bid for each product
   $highest_bid_query = $db->getConnection()->prepare("SELECT id_user, id_product, MAX(price) AS highest_price FROM tb_auction GROUP BY id_product");
   $highest_bid_query->execute();
   $result = $highest_bid_query->get_result();

   while ($highest_bid = $result->fetch_assoc()) {
      // var_dump($highest_bid);
      // echo "<br>";
      // var_dump($highest_bid['id_user']);

      // // Check if the highest bid for any product is from the specified user
      if ($highest_bid['id_user'] == $data_user) {
         // If this user has the highest bid, insert it into the cart
         $insert_cart_query = $db->getConnection()->prepare("INSERT INTO tb_cart (id_user, id_address, id_product, price) VALUES (?, ?, ?, ?)");
         $insert_cart_query->bind_param("ssss", $highest_bid['id_user'], $address_id, $highest_bid['id_product'], $highest_bid['highest_price']);
         $insert_cart_query->execute();
      }
   }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>LelangIN | Cart</title>

   <!-- Bootstrap -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
   <!-- <link rel="stylesheet" href="../style/style_cartt.css"> -->
</head>

<body>
   <div class="container">
      <section class="shopping-cart">
         <h1 class="heading">Cart</h1>
         <form action="" method="post" enctype="multipart/form-data">
            <table class="table table-striped table-hover">
               <thead>
                  <tr>
                     <th>Image</th>
                     <th>Name</th>
                     <th>Quantity</th>
                     <th>Price</th>
                     <th>Option</th>
                  </tr>
               </thead>
               <tfoot>
                  <tr>
                     <th>Image</th>
                     <th>Name</th>
                     <th>Quantity</th>
                     <th>Price</th>
                     <th>Option</th>
                  </tr>
               </tfoot>
               <tbody>
                  <?php
                  if (!empty($id_user)) {
                     $query_show_cart = $db->getConnection()->prepare("SELECT tb_product.id_product, tb_product.name, tb_product.image, tb_product.price, tb_product.quantity FROM tb_cart JOIN tb_product ON tb_cart.id_product = tb_product.id_product WHERE tb_cart.id_user = ?");
                     $query_show_cart->bind_param("s", $id_user);
                     $query_show_cart->execute();
                     $result_cart = $query_show_cart->get_result();

                     if ($result_cart->num_rows > 0) {
                         while ($cart_item = $result_cart->fetch_assoc()) {
                     ?>
                             <tr>
                                 <td>
                                    <img src="../data_image/<?= htmlspecialchars($cart_item['image']); ?>" height="100" alt="">
                                    <input type="hidden" name="id_user" value="<?= $id_user; ?>">
                                    <input type="hidden" name="id_product" value="<?= $cart_item['id_product'] ?>">
                                    <input type="hidden" name="id_product" value="<?= $address_id; ?>">
                                    
                                 </td>
                                 <td><?= htmlspecialchars($cart_item['name']); ?></td>
                                 <input type="hidden" name="price" value="<?= $cart_item['name'];?>">
                                 <td>
                                    <input type='number' name='quantity' value="<?= htmlspecialchars($cart_item['quantity']); ?>" disabled>
                                 </td>
                                 <td>
                                    <?= htmlspecialchars($cart_item['price']); ?>
                                    <input type="hidden" name="price" value="<?= $cart_item['price'];?>">
                                 </td>
                                 <td>
                                     <!-- Button Edit Address -->
                                     <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit_address">
                                         Edit Address
                                     </button>
                                 </td>
                             </tr>
                     <?php
                         }
                     } else {
                         echo '<tr><td colspan="5">Oops! Your cart is empty.</td></tr>';
                     }
                  }
                     ?>
                     
                  </tr>
               </tbody>
            </table>
            <div class="checkout-btn">
               <button type="submit" name="checkout" class="btn btn-warning">Add</button>
            </div>
         </form>
         <!-- Modal Edit address -->
         <div class="modal fade" id="edit_address" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title">Edit Address</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body">
                     <!-- Form content here -->
                     <div class="form_reg">
                        <form method="POST" action="" enctype="multipart/form-data">
                           <div class="form-group">
                              <label for="desa">Desa</label>
                              <input type="text" class="form-control" name="desa" value="<?= $address_desa; ?>">
                              <input type="hidden" name="address_id" value="<?= $address_id; ?>">
                              <input type="hidden" name="user_id" value="<?= $id_user ?>">
                           </div>
                           <div class="form-group">
                              <label for="kecamatan">Kecamatan</label>
                              <input type="text" class="form-control" id="kecamatan" name="kecamatan" value="<?= $address_kecamatan; ?>" placeholder="Enter your email" required>
                           </div>
                           <div class="form-group">
                              <label for="kota">Kota</label>
                              <input type="text" class="form-control" id="kota" name="kota" placeholder="Enter your kota" value="<?= $address_kota; ?>" required>
                           </div>
                           <div class="form-group">
                              <label for="provinsi">Provinsi</label>
                              <input type="text" class="form-control" id="provinsi" name="provinsi" placeholder="Enter your provinsi" value="<?= $address_provinsi; ?>" required>
                           </div>
                           <div class="form-group">
                              <label for="negara">negara</label>
                              <input type="text" class="form-control" id="negara" name="negara" placeholder="Enter your negara" value="<?= $address_negara; ?>" required>
                           </div>
                           <div class="submit">
                              <button type="submit" name="edit_address" class="btn btn-primary">Edit</button>
                              <!-- <a href="logout.php" class="btn btn-danger">Log Out</a> -->
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
   </div>
   </sect ion>
</body>

</html>