<?php
require 'connection.php';
// require 'function.php';

require 'timer.php';
$db = new database();

//set time to asia jakarta
date_default_timezone_set('Asia/Jakarta');

$query = $db->getConnection()->query("SELECT * FROM tb_countdown ORDER BY id DESC LIMIT 1");
$data = $query->fetch_assoc();

$datetime = $data['date'] . " " . $data['hour'] . ":" . $data['minute'] . ":" . $data['second'];
$datetime = strtotime($datetime);
// $timestamp = strtotime($datetime);
// var_dump($datetime);
// var_dump($timestamp);
// exit();

session_start();
if (!isset($_SESSION['username'])) {
    header('location: ../account/login.php');
}


if ($_SESSION['role'] != "admin") {
    header("Location: ../index.php");
}


//add product
if (isset($_POST['add_product'])) {
    $name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Proses upload gambar
    $image = $_FILES['image']['name'];

    $query = new product(null, $image, $name, $description, $price);
    $query->addProduct();
}

// if edit button was clicked
if (isset($_POST['edit_product'])) {

    $id = $_POST['id_product'];
    $name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    // Proses upload gambar
    $image = $_FILES['image']['name'];

    //jika data image kosong
    if (empty($image)) {
        $query = new product($id, null, $name, $description, $price);
        $query->editProduct();
    } else { //jika ada data dalam $image
        $query = new product($id, $image, $name, $description, $price);
        $query->editProduct();
    }
}

//delete product
if (isset($_POST['delete_product'])) {
    $id = $_POST['id_product'];

    $query = new product($id, null, null, null, null);
    $query->deleteProduct();
}

//input time into database
if (isset($_POST['auction_option'])) {
    $id_product = $_POST['id_product'];
    $date = $_POST['date'];
    $hour = $_POST['hour'];
    $minute = $_POST['minute'];
    $second = $_POST['second'];

    $query = new timer($id_product, $date, $hour, $minute, $second);
    $query->time();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>LelangIn | Admin-Product</title>

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

    <script>
        //receive data from form
        document.addEventListener('DOMContentLoaded', function() {
            let countDownDate = <?= $datetime * 1000; ?>;
            let now = <?= time() * 1000 ?>;
            console.log("Countdown Date: " + new Date(countDownDate));
            console.log("Now: " + new Date(now));


            const x = setInterval(function() {
                //  var now = now + 1000;
                now = Date.now();
                const distance = countDownDate - now;
                // if (distance >= 0) {
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                //display the result in the element with id = "demo"
                document.getElementById("demo").innerHTML = days + "d " + hours + "h " +
                    minutes + "m " + seconds + "s ";
                // } 

                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("demo").innerHTML = "EXPIRED";
                    sendDataToPHP();
                }
            }, 1000);
        });

            function sendDataToPHP() {

                var id_product = document.getElementById("uniqueIdProduct").value;
                console.log(id_product);

                //send data to php 
                fetch('ajax_timer.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `data=expired&id_product=${id_product}`,
                    })
                    .then(response => response.text())
                    .then(result => {
                        console.log(result);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        </script>

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
                            Add Product
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
                                                <th>date added</th>
                                                <th>date closed</th>
                                                <th>status</th>
                                                <th>Option</th>
                                                <th>Countdown</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>id_product</th>
                                                <th>image</th>
                                                <th>name</th>
                                                <th>Description</th>
                                                <th>price</th>
                                                <th>Date added</th>
                                                <th>Date closed</th>
                                                <th>status</th>
                                                <th>Option</th>
                                                <th>Countdown</th>
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
                                                    <td><img src="../data_image/<?php echo $rows['image']; ?>" style="width: 250px; height: 250px; object-fit: contain;"></td>
                                                    <td><?= $rows['name']; ?></td>
                                                    <td><?= $rows['description']; ?></td>
                                                    <td><?= $rows['price']; ?></td>
                                                    <td><?= $rows['date_added']; ?></td>
                                                    <td><?= $rows['date_closed']; ?></td>
                                                    <td><?= $rows['status']; ?></td>
                                                    <td>
                                                        <!-- Start the Auction -->
                                                        <button type="button mb-5" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#product_timer_<?php echo $rows['id_product']; ?>">
                                                            Start Auction
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="product_timer_<?php echo $rows['id_product']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel"><?php echo $rows['name']; ?></h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <form method="post" action="" enctype="multipart/form-data" id="input_id">
                                                                        <input type="hidden" name="id_product" id="uniqueIdProduct" value="<?= $rows['id_product']; ?>">
                                                                        <div class="input-group mb-3">
                                                                            <input type="hidden" name="id_product" value="<?= $rows['id_product']; ?>">
                                                                            <input type="date" class="form-control" name="date" id="date" placeholder="date">
                                                                        </div>
                                                                        <div class="input-group mb-3">
                                                                            <input type="number" class="form-control" name="hour" id="hour" placeholder="hour">
                                                                        </div>
                                                                        <div class="input-group mb-3">
                                                                            <input type="number" class="form-control" name="minute" id="minute" placeholder="minute">
                                                                        </div>
                                                                        <div class="input-group mb-3">
                                                                            <input type="number" class="form-control" name="second" id="second" placeholder="second">
                                                                        </div>
                                                                        <div class="submit">
                                                                            <button type="submit" name="auction_option" class="btn btn-primary">Auction Time</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                </div>

                                <!-- EDIT PRODUCT -->
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
                                                    <input type="hidden" name="id_product" id="id_product" value="<?= $rows['id_product']; ?>">

                                                    <div class="form-group">
                                                        <label for="image" class="form-label">Image</label>
                                                        <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png" value="<?= $rows['image']; ?>">
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
                                <!-- Delete -->
                                <form action="" method="POST">
                                    <input type="hidden" value="<?= $rows['id_product']; ?>" name="id_product">
                                    <button type="submit" class="btn btn-danger" name="delete_product" id="delete_product">
                                        delete product
                                    </button>
                                </form>
                                </td>
                                <td>
                                    <?php 
                                        $query = "SELECT tb_product.id_product AS product_id, tb_countdown.id_product AS countdown_id 
                                                FROM tb_product INNER JOIN tb_countdown ON tb_product.id_product = tb_countdown.id_product";
                                    ?>
                                        <p id="demo"></p>
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

    <!-- Javascript timezone file -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.33/moment-timezone-with-data-10-year-range.min.js"></script>

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