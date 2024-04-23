<?php

require 'function.php';

//open database
$db = new database();

//INNER JOIN tb_account & tb_address
$query = "SELECT tb_account.id_user, tb_address.id_address FROM tb_account INNER JOIN tb_address ON tb_account.id_user = tb_address.id_user";
$query = $db->getConnection()->query($query);

if($query->num_rows > 0) {
    while($data = $query->fetch_assoc()) {
        $data_user = $data['id_user'];
        $data_address = $data['id_address'];
    }
}

// var_dump($data_user);
// var_dump($data_address);
// exit();

// Get query from tb_account
$sql_account = "SELECT * FROM `tb_account`";
$result = $db->getConnection()->query($sql_account);

//get query from tb_auction
$sql_auction = "SELECT * FROM `tb_auction` ORDER BY `price` DESC";
$data_auction = $db->getConnection()->query($sql_auction);
$data_auction = $data_auction->fetch_assoc();

//get query from tb_product
$sql_product = "SELECT * FROM `tb_product` ORDER BY `status` DESC";
$data_product = $db->getConnection()->query($sql_product);
$data_product = $data_product->fetch_assoc();

//inner join tb_account and tb_auction
// $query = "SELECT tb_account.username, tb_account.id_user, highest_price_auction.price
//     FROM tb_account INNER JOIN 
//     (SELECT * FROM tb_auction ORDER BY price DESC LIMIT 1) AS highest_price_auction 
//     ON tb_account.id_user = highest_price_auction.id_user";
// $query = $db->getConnection()->query($query);
// $data_auction = $query->fetch_assoc();

// $data_auction['price'];
// var_dump($data_auction['price']);

//check data
var_dump($data_auction);
echo "<hr>";
var_dump($data_product['id_product']);
var_dump($data_product['status']);
echo "<hr>";

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        if ($data_product['status'] == "expired") {
            if($data_auction['id_user'] == $row['id_user']) {
                $sql_insert = "INSERT INTO `tb_cart`(`id_user`, `id_address`, `id_product`) 
                                VALUES (?,?,?)";
                $sql_insert = $db->getConnection()->prepare($sql_insert);
                $sql_insert->bind_param("sss", $row['id_user'], $data_address, $data_product['id_product']);
                if($sql_insert = $sql_insert->execute()) 
                {
                    header("Location: cart.php");
                } else {
                    header("Location: index.php");
                }
            }
        }
    }
} else {
    echo "0 results";
}
