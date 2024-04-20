<?php

include '../admin/function.php';

$db = new database();

var_dump($_POST);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['data']) && isset($_POST['id_product'])) {

        $data = $_POST['data'];
        $id_product = $_POST['id_product'];

        var_dump($id_product);

        //mengambil data tanggal close
        date_default_timezone_set("Asia/Jakarta");
        $date_clossed = date("Y-m-d h-m-s");

        $query = $db->getConnection()->prepare("UPDATE `tb_product` SET `status`=?,`date_closed`=? WHERE `id_product` = ?");
        $query->bind_param("sss", $data, $date_clossed,$id_product);
        $query->execute();

        if ($query) {
            echo "<script>
            alert('data berhasil ditambahkan');
        </script>";
        } else {
            echo "Error: " . $sql . "<br>";
        }
    }
}