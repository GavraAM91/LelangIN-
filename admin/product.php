<?php
include 'connection.php';
require 'function.php';

// session_start();
// if (!isset($_SESSION['username'])) {
//     header('location: ../account/login.php');
// }


//add product
if (isset($_POST['add_product'])) {
    $name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Proses upload gambar
    $image = $_FILES['image']['name'];
    $ukuran_file = $_FILES['image']['size'];
    $error = $_FILES['image']['error'];
    $tmp_file = $_FILES['image']['tmp_name'];

    if ($error === 4) {
        echo "<script>alert('pilih gambar terlebih dahulu!');</script>";
        header("Location: product.php");
        return false;   
    }

    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $image);
    $ekstensiGambar = strtolower(end($ekstensiGambar));

    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>alert('yang anda upload bukan gambar!');</script>";
        return false;
    }

    if ($ukuran_file > 500000) {
        echo "<script>alert('ukuran terlalu besar!');</script>";
        header("Location: product.php");
        exit;
    }

    $newImage = uniqid() . '.' . $ekstensiGambar;
    $storage = '../data_image/' . $newImage;

    if (move_uploaded_file($tmp_file, $storage)) {
        // Pastikan constructor class `product` sesuai dengan parameter yang diberikan
        $product = new product($newImage, $name, $description, $quantity, $price);
        $product->addProduct();
    } else {
        echo "Gagal mengunggah gambar. Kode Kesalahan: " . $error;  
        echo "<br><a href='product.php'>Kembali Ke Form</a>";
    }
}

// if (isset($_POST['edit_product'])) {
//     $id = $_POST['id_product'];
//     $name = $_POST['product_name'];
//     $description = $_POST['description'];
//     $price = $_POST['price'];
//     $quantity = $_POST['quantity'];

//     // Proses upload gambar
//     $image = $_FILES['image']['name'];
//     $ukuran_file = $_FILES['image']['size'];
//     $error = $_FILES['image']['error'];
//     $tmp_file = $_FILES['image']['tmp_name'];

//     if ($error === 4) {
//         echo "<script>alert('pilih gambar terlebih dahulu!');</script>";
//         header("Location: product.php");
//         return false;   
//     }

//     $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
//     $ekstensiGambar = explode('.', $image);
//     $ekstensiGambar = strtolower(end($ekstensiGambar));

//     if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
//         echo "<script>alert('yang anda upload bukan gambar!');</script>";
//         return false;
//     }

//     if ($ukuran_file > 500000) {
//         echo "<script>alert('ukuran terlalu besar!');</script>";
//         header("Location: product.php");
//         exit;
//     }

//     $newImage = uniqid() . '.' . $ekstensiGambar;
//     $storage = '../data_image/' . $newImage;
    
//     if (move_uploaded_file($tmp_file, $storage)) {
//         // Pastikan constructor class `product` sesuai dengan parameter yang diberikan
//         $product = new product($id, $newImage, $name, $description, $quantity, $price);
//         $product->editProduct(); 
//     } else {
//         echo "Gagal mengunggah gambar. Kode Kesalahan: " . $error;  
//         echo "<br><a href='product.php'>Kembali Ke Form</a>";
//     }
// }


?>

<!DOCTYPE html>
<html lang="en">

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Sidebar -->
            <?php
            include('sidebar.html');
            ?>

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Topbar -->
                    <?php
                    include('../admin/topbar.html');
                    ?>


                    <!-- Begin Page Content -->
                    <div class="container-fluid">

                        <!-- Page Heading -->
                        <h1 class="h3 mb-2 text-gray-800">Product</h1>
                        <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                            For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p>


                        <!-- Button Add Product-->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#productModal">
                            Tambah Buku
                        </button>

                        <!-- Modal ADD PRODUCT -->
                        <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Product</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form content here -->
                                        <div class="form_reg">
                                            <form method="POST" action="" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label for="image">Insert Image </label>
                                                    <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png" value="">
                                                </div>
                                                <div class="form-group">
                                                    <label for="product_name">product name</label>
                                                    <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">description </label>
                                                    <input type="text" class="form-control" id="description" name="description" placeholder="Enter description" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="price">Price </label>
                                                    <input type="text" class="form-control" id="price" name="price" placeholder="Enter price Rp." required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="quantity">Quantity </label>
                                                    <input type="text" class="form-control" id="quantity" name="quantity" placeholder=" quantity" required>
                                                </div>
                                                <div class="submit">
                                                    <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Delete All Product-->
                        <a href="product_add.php" class="btn btn-danger">
                            <span class="text">Delete All Product</span>
                        </a>

                        <!-- DataTales Example -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Product Table </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>id_product</th>
                                                <th>image</th>
                                                <th>name</th>
                                                <th>Description</th>
                                                <th>price</th>
                                                <th>Quantity</th>
                                                <th>Time</th>
                                                <th>status</th>
                                                <th>Option</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>id_product</th>
                                                <th>image</th>
                                                <th>name</th>
                                                <th>Description</th>
                                                <th>price</th>
                                                <th>Quantity</th>
                                                <th>Date added</th>
                                                <th>status</th>
                                                <th>Option</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            $sql = $connection->query("SELECT * FROM `tb_product`");

                                            while ($rows = mysqli_fetch_array($sql)) :
                                            ?>
                                                <tr>
                                                    <td><?= $rows['id_product']; ?></td>
                                                    <td><img src="../data_image/<?php echo $rows['image']; ?>" style="width: 250px; height: 250px;"></td>
                                                    <td><?= $rows['name']; ?></td>
                                                    <td><?= $rows['description']; ?></td>
                                                    <td><?= $rows['price']; ?></td>
                                                    <td><?= $rows['quantity']; ?></td>
                                                    <td><?= $rows['date_added']; ?></td>
                                                    <td><?= $rows['status']; ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#product_data_<?php echo $rows['id_product']; ?>">
                                                            Edit Product
                                                        </button>
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="product_data_<?php echo $rows['id_product']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel"><?php echo $rows['name']; ?></h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form method="post" action="" enctype="multipart/form-data">
                                                                        <input type="hidden" name="id_product" value="<?= $rows['id_product']; ?>">
                                                                            <div class="form-group">
                                                                                <label for="image" class="form-label">image : </label>
                                                                                <input type="file" class="form-control" name="image" id="image" placeholder="Masukkan image" value="<?= $rows['image']; ?>">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="product_name">product name</label>
                                                                                <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name" required value="<?= $rows['name']; ?>">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="description">description </label>
                                                                                <input type="text" class="form-control" id="description" name="description" placeholder="Enter description" required value="<?= $rows['description']; ?>">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="price">Price </label>
                                                                                <input type="text" class="form-control" id="price" name="price" placeholder="Enter price Rp." required value="<?= $rows['price']; ?>">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="quantity">Quantity </label>
                                                                                <input type="text" class="form-control" id="quantity" name="quantity" placeholder=" quantity" required value="<?= $rows['quantity']; ?>">
                                                                            </div>
                                                                            <div class="submit">
                                                                                <button type="submit" name="edit_product" class="btn btn-primary">Edit Product</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete_data_<?php echo $rows['id_product']; ?>">
                                                            Delete
                                                        </button> -->
                                                    
                                                    </td>
                                                </tr>
                                            <?php
                                            endwhile;
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    </div>

    <!-- /.container-fluid -->

    </div>

    <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>