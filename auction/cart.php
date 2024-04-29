<?php
require_once 'function.php';

// if(isset($_POST['checkout'])){  // Changed to POST method
//    $select_cart = mysqli_query($conn, "SELECT * FROM `cart`");
//    if(mysqli_num_rows($select_cart) == 0){
//       echo '<script>alert("Your cart is empty. Add items before checkout.");</script>';
//       header('location:users\index.php'); // Redirect to the homepage if the cart is empty
//       exit(); // Added exit to stop further execution
//    } else {
//       header('location:checkout.php'); // Proceed to checkout if the cart is not empty
//    }
//}

$db = new database();

//open database tb_account

//check session
session_start();
$username = $_SESSION['username'];

$query_profile = $db->getConnection()->query("SELECT * FROM tb_account");
if ($query_profile) {
   while ($data_profile = $query_profile->fetch_assoc()) {
      $id_user = $data_profile['id_user'];
      $user_name = $data_profile['username'];
   }
}

if ($username == $user_name) {
   $query = $db->getConnection()->prepare("SELECT * FROM tb_auction WHERE id_user = ?");
   $query->bind_param("s", $id_user);
   $query->execute();
   $result = $query->get_result();

   while($data = $result->fetch_assoc()) {




// $query = $db->getConnection()->prepare("SELECT * FROM tb_auction WHERE id_user = ?");
// $query->bind_param("s", $id_user)
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>LelangIN | Cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../style/style_cart.css">
</head>

<body>

   <?php
   // include 'header.php';

   // while($data = $result->fetch_assoc()) {
   ?>

   <div class="container">
      <section class="shopping-cart">
         <h1 class="heading">Cart</h1>
         <form action="" method="post"> <!-- Assuming you want to use the GET method -->
            <table>
               <thead>
                  <th>image</th>
                  <th>name</th>
                  <th>quantity</th>
               </thead>
               <tbody>
                  <?php
                  $db = new database();
                  $data_cart = $db->getConnection()->query("SELECT * FROM tb_cart");
                  $grand_total = 0;
                  foreach ($data_cart as $data) {
                  ?>
                     <tr>
                        <td><img src="image_data/<?php echo $data['image']; ?>" height="100" alt=""></td>
                        <td><?php echo $data['name']; ?></td>
                        <td>
                           <form action="" method="post">
                              <input type="hidden" name="update_quantity_id" value="<?php echo $data['id_cart']; ?>">
                              <input type="number" name="update_quantity" value="<?php echo $data['price']; ?>" disabled>
                           </form>
                        </td>
                     </tr>
                  <?php
                     $grand_total   =  $data['quantity']; // Update grand total
                  }
                  ?>
               </tbody>
            </table>
            <div class="checkout-btn">
               <input type="submit" name="checkout" class="btn btn-primary <?= ($grand_total > 1) ? '' : ''; ?>" value="checkout">
            </div>
         </form>
      </section>
   </div>
      
   <!-- custom js file link  -->
   <script src="js/script.js"></script>
   
   <?php 
      }  
   }
   ?>
</body>

</html>
