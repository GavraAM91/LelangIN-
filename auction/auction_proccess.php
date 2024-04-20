<?php

require 'function.php';

//open database
$db = new database();

//get query from tb_account
$sql_account = "SELECT * FROM `tb_account`";
$sql_account = $db->getConnection()->query($sql_account);
$data_account = $sql_account->fetch_assoc();

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

if ($data_product['status'] == "expired") {
    if($data_auction['id_user'] == $data_account['id_user']) {
        echo "hello";
    }
}
