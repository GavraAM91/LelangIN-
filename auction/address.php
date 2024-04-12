<?php
require 'function.php';
//open data database 
$db = new database();

//open database tb_account

//open database tb_address

//jika tombol address_button dipencet
if(isset($_POST['address_button'])) {

    $desa = $_POST['desa'];
    $kecamatan = $_POST['kecamatan'];
    $kota = $_POST['kota'];
    $provinsi = $_POST['provinsi'];
    $negara = $_POST['negara'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LelangIn | address</title>

    <!-- CSS -->

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="form">
        <h1 class="title">Address</h1>
        <form action="" method="post" class="row g-3">
            <div class="col-md-6">
                <label for="desa" class="form-label">Desa</label>
                <input type="text" class="form-control" id="desa" name="desa" placeholder="nama desa">
                <input type="hidden" name="id_user" value>
            </div>
            <div class="col-md-6">
                <label for="kecamatan" class="form-label">kecamatan</label>
                <input type="text" class="form-control" id="kecamatan" name="kecamatan" placeholder="nama kecamatan">
            </div>
            <div class="col-md-6">
                <label for="kota" class="form-label">kota</label>
                <input type="text" class="form-control" id="kota" name="kota" placeholder="nama kota">
            </div>
            <div class="col-md-6">
                <label for="provinsi" class="form-label">provinsi</label>
                <input type="text" class="form-control" id="provinsi" name="provinsi" placeholder="nama provinsi">
            </div>
            <div class="col-md-6">
                <label for="negara" class="form-label">negara</label>
                <input type="text" class="form-control" id="negara" name="negara" placeholder="nama negara">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary" name="address_button">Submit</button>
            </div>
        </form>

    </div>
</body>

</html>