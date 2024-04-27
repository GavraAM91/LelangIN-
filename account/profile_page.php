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
require_once '../components/header.php';
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
                                        <a href="logout.php" class="btn btn-danger">Log Out</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="address">
            <div class="form-address">
                <h1 class="title">Address</h1>
                <form action="" method="POST" class="row g-3">
                    <div class="col-md-6">
                        <label for="desa" class="form-label">Desa</label>
                        <input type="text" class="form-control" id="desa" name="desa" placeholder="nama desa" disabled>
                        <input type="hidden" name="id_user" value="<?= $user_id ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="kecamatan" class="form-label">kecamatan</label>
                        <input type="text" class="form-control" id="kecamatan" name="kecamatan" placeholder="nama kecamatan" disabled>
                    </div>
                    <div class="col-md-6">
                        <label for="kota" class="form-label">kota</label>
                        <input type="text" class="form-control" id="kota" name="kota" placeholder="nama kota" disabled>
                    </div>
                    <div class="col-md-6">
                        <label for="provinsi" class="form-label">provinsi</label>
                        <input type="text" class="form-control" id="provinsi" name="provinsi" placeholder="nama provinsi" disabled>
                    </div>
                    <div class="col-md-6">
                        <label for="negara" class="form-label">negara</label>
                        <input type="text" class="form-control" id="negara" name="negara" placeholder="nama negara" disabled>
                    </div>
                </form>

                <!-- Button to Address page -->
                <a href="../auction/address.php" class="btn btn-primary" style="margin-top: 20px;">Address Setting</a>
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