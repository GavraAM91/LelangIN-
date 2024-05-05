<?php
//connection into function
require 'function.php';

//open database
$db = new database();

//start the session
session_start();

//insert value to variable username
$username = $_SESSION['username'];

$query = $db->getConnection()->prepare("SELECT id_user FROM tb_account WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id_user = $row['id_user'];
} else {
    echo "Username tidak ditemukan.";
}

//check if user doesn't login
if (!isset($username)) {
    //direct into login page 
    header("Location: login.php");
    exit();
}

//OPEN tb_

//INNER JOIN tb_account & tb_address
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
$query = $db->getConnection()->prepare("SELECT * FROM `tb_account` WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();

// $query->execute();

// //get result with diff variable 
// $data_user = $query->get_result();
// $data_user = $data_user->fetch_assoc();

// var_dump($data_user['password']);

//edit profile data
if (isset($_POST['edit_profile'])) {
    $image = $_FILES['image']['name'];

    $user_id = $_POST['id_user'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = new account($image, $username, $password, null, $email, null, $user_id);
    $query->editAccount();
}

//edit address data
if(isset($_POST['edit_address'])) {
    $id_user = $_POST['user_id'];
    $address_id = $_POST['address_id'];
    $address_desa = $_POST['desa'];
    $address_kecamatan = $_POST['kecamatan'];
    $address_kota = $_POST['kota'];
    $address_provinsi = $_POST['provinsi'];
    $address_negara = $_POST['negara'];

    $query = new address($id_user, $address_id, $address_desa, $address_kecamatan, $address_kota, $address_provinsi, $address_negara);
    $query->address();
}
//logout
if (isset($_POST['logout'])) {

    $id_user = $_POST['id_user'];

    $query = new account(null, null, null, null, null, null, $id_user);
    $query->logout();
}
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

<?php
require '../components/header.php';
?>

<?php
while ($data_user = $result->fetch_assoc()) :
?>

    <body>
        <div class="account">
            <h1 class="judul text-center">Account Page</h1>
            <div class="personal-info p-3 rounded">
                <!-- Container untuk profil dan detil -->
                <div class="profile-container">
                    <!-- Container untuk detil username dan email -->
                    <div class="profile-details">
                        <!-- Container untuk gambar profil -->
                        <div class="profile-image">
                            <img src="../profile_image/<?= $data_user['image']; ?>" alt="Profile Image">
                        </div>
                        <div class="detail">
                            <h3>Username</h3>
                            <p><?= $data_user['username']; ?></p>

                            <h3>Email</h3>
                            <p><?= $data_user['email']; ?></p>
                            <!-- Button Edit Profile -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit_profile">
                                Edit
                            </button>
                            <!-- Button Edit Address -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit_address">
                                Edit Address
                            </button>
                            <form action="" method="post">
                                <input type="hidden" name="user_id" value="<?= $data_user['id_user']; ?>">
                                <button type="submit" name="logout" class="btn btn-danger">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Edit Profile -->
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
                                        <input type="file" name="image" id="image" class="form-control" accept=".jpg, .jpeg, .png" value="<?= $data_user['image']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" value="<?= $data_user['username']; ?>" placeholder="Enter your username" required>
                                        <input type="hidden" id="id_user" name="id_user" value="<?= $data_user['id_user'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" id="email" name="email" value="<?= $data_user['email']; ?>" placeholder="Enter your email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="Password">New Password</label>
                                        <input type="text" class="form-control" id="password" name="password" placeholder="Enter your new password" required>
                                    </div>
                                    <div class="submit">
                                        <button type="submit" name="edit_profile" class="btn btn-primary">Edit</button>
                                        <!-- <a href="logout.php" class="btn btn-danger">Log Out</a> -->
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
    <?php endwhile; ?>
    <!-- Auction History Section -->
    </div>
    <div class="auction-history">
        <h3 class="judul mb-3">Auction History</h3>
    </div>

    </body>

</html>