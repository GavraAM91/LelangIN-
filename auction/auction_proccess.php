<?php 

require 'function.php';

//open database
$db = new database();

//get query from tb_auction

//get query from tb_product
$sql_product = "SELECT * FROM `tb_product` ORDER BY `status` DESC";
$data_product = $db->getConnection()->query($sql_product);

$data_product = $data_product->fetch_assoc();
var_dump($data_product);



?>