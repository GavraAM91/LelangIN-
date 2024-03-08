<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Product</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<?php

include 'connection.php';
include 'function.php';

$id_product = $_GET['id'];

//ambil data    
$query = $connection->query("SELECT * FROM tb_product WHERE id_product = '$id_product'");

while ($row = mysqli_fetch_array($query)) {
?>

    <!-- Form content here -->
    <div class="form_reg">
        <form method="post" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image" class="form-label">image : </label>
                <input type="file" class="form-control" name="image" id="image" placeholder="Masukkan nama image" value="<?= $row['image']; ?>">
            </div>
            <div class="form-group">
                <label for="product_name">product name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name" required value="<?= $row['name']; ?>">
            </div>
            <div class="form-group">
                <label for="description">description </label>
                <input type="text" class="form-control" id="description" name="description" placeholder="Enter description" required value="<?= $row['description']; ?>">
            </div>
            <div class="form-group">
                <label for="price">Price </label>
                <input type="text" class="form-control" id="price" name="price" placeholder="Enter price Rp." required value="<?= $row['price']; ?>">
            </div>
            <div class="form-group">
                <label for="quantity">Quantity </label>
                <input type="text" class="form-control" id="quantity" name="quantity" placeholder=" quantity" required value="<?= $row['quuantity']; ?>">
            </div>
            <div class="submit">
                <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
            </div>
        </form>
    </div>

<?php
}
?>