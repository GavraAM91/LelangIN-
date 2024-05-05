<?php 
include 'function.php';

session_start();

$db = new database();

$username = $_SESSION['username'];

//INNER JOIN    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LelangIn | Checkout page</title>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
      <!-- <link rel="stylesheet" href="../style/style_cartt.css"> -->
</head>
<body>
<table class="table table-hover">
    <h2>ORDERED PRODUCT</h2>
    <thead>
        <th>image</th>
        <th>name</th>
        <th>address</th>
    </thead>
    <tbody>

    </tbody>
</table>
</body>
</html>