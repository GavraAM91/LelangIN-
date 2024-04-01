<?php 

// ajax_timer.php
require 'function.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['auction_time'])) {
    $auction_time = $_POST['auction_time'];
    
    $timer = new timer($auction_time);

    $data = $timer->time(); // Sekarang $data berisi array yang dikembalikan oleh time()

     // Menambahkan data ke file JSON
     $fileJson = 'data.json';
     $existingData = json_decode(file_get_contents($fileJson), true) ?: [];
     $existingData[] = $data;
     file_put_contents($fileJson, json_encode($existingData, JSON_PRETTY_PRINT));
 
     header('Content-Type: application/json');
     json_encode($data); 
     header("Location: product.php");

} else {
    http_response_code(400);
    echo json_encode(['error' => 'Bad Request']);
}
