<?php
//connection into function
require 'function.php';

//start the session
session_start();

//insert value to variable username
$username = $_SESSION['username'];

//check if user doesn't login
if (!isset($username)) {
    //direct into login page 
    header("Location: login.php");
    exit();
}

//open database
$db = new database();

//inner join table account and table address
$sql = "SELECT tb_account.username FROM `tb_account` JOIN tb_address ON tb_address.id_user = tb_account.id_user";

$query = $db->getConnection()->prepare("SELECT * FROM `tb_account` WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();

//get result with diff variable 
$data_user = $query->get_result();
$data_user = $data_user->fetch_assoc();



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LelangIN | Account</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- CSs -->
    <link rel="stylesheet" href="../style/style_profile.css">
</head>

<body>
    <h1 class="judul text-center">Account Page</h1>
    <div class="personal-info p-3 rounded">
        <div class="row">
            <div class="profile">
                <img src="../profile_image/<?= $data_user['image']; ?>" alt="" style="height: 200px; width:200px;">
                <!-- Detail Profile untuk Username -->
                <div class="col-md-6 mb-3">
                    <h3>Username</h3>
                    <div class="detail-profile">
                        <p><?= $data_user['username']; ?></p>
                    </div>
                </div>

                <!-- Detail Profile untuk Email -->
                <div class="col-md-6 mb-3">
                    <h3>Email</h3>
                    <div class="detail-profile">
                        <p><?= $data_user['email']; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button Add Product-->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit_profile">
            Edit_Profile
        </button>

        <!-- Modal ADD PRODUCT -->
        <div class="modal fade" id="edit_profile" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
                                    <label for="description">Quantity </label>
                                    <input type="text" class="form-control" id="quantity" name="quantity" value=1 placeholder="Enter description" disabled>
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
    </div>



    <!-- Auction History Section -->
    </div>
    <div class="auction-history">
        <h3 class="judul mb-3">Auction History</h3>
    </div>

</body>

</html>