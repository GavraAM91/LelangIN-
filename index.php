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
                    <button><a href="signin.php">SignIn</a></button>
                    <button><a href="signup.php">SignUp</a></button> 
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

        <div class="card">
            <img src="IMG/marsha-jkt48-4.jpg" alt="Placeholder Image" class="card-img">
            <div class="card-body">
                <h5 class="card-title">Card Title</h5>
                <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
            </div>
        </div>
    </div>

    <div class="footer">
        
    </div>

</body>

</html>