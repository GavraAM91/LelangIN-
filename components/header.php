<html>

<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="../style/style.css">
</head>
<?php
session_start();
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
        <li><a href="/auction/cart.php">Cart</a></li>
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
        <li><a href="auction/cart.php">Cart</a></li>
        <button><a href="account/profile_page.php">account</a></button>
      </ul>
    </div>
  </nav>
<?php
}
?>

</html>