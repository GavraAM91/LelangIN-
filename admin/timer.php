<?php
session_start();
include 'function.php';
$db = new database();
date_default_timezone_set('Asia/Jakarta');

$username = $_SESSION['username'];

// Get user and address details for the logged-in user
$query_account = $db->getConnection()->prepare("SELECT tb_account.id_user, tb_address.id_address FROM tb_account INNER JOIN tb_address ON tb_account.id_user = tb_address.id_user WHERE username = ?");
$query_account->bind_param("s", $username);
$query_account->execute();
$user_data = $query_account->get_result()->fetch_assoc();

$id_user = $user_data['id_user'] ?? null;
$address_id = $user_data['id_address'] ?? null;

// Check if there's a POST request to update the product status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['data'], $_POST['id_product'])) {
    $status = $_POST['data'];
    $id_product = $_POST['id_product'];
    $date_closed = date("Y-m-d H:i:s");

    $update_query = $db->getConnection()->prepare("UPDATE tb_product SET status=?, date_closed=? WHERE id_product=?");
    $update_query->bind_param("ssi", $status, $date_closed, $id_product);
    $update_query->execute();

    if ($update_query->affected_rows > 0 && $status === 'expired') {
        echo "<script>alert('Data berhasil diupdate!');</script>";
    } else {
        echo "<script>alert('Update gagal atau tidak ada data yang berubah.');</script>";
    }
}


//get highest price in tb_auction
// $sql_auction = $db->getConnection()->prepare("SELECT * FROM tb_auction WHERE id_user = ?");
// $sql_auction->bind_param("s", $id_user);
// $sql_auction->execute();

//input the highest price into tb_cart from the id_product into tb_auction


// Get the last countdown time from database
$query = $db->getConnection()->query("SELECT * FROM tb_countdown ORDER BY id DESC LIMIT 1");
$data = $query->fetch_assoc();

if ($data !== null) {
    $datetime = strtotime($data['date'] . " " . $data['hour'] . ":" . $data['minute'] . ":" . $data['second']);
}
$jsonData = json_encode(['id_product' => $data['id_product']]);
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let countDownDate = <?= $datetime * 1000; ?>;
        let now = <?= time() * 1000; ?>;

        const interval = setInterval(function() {
            now = Date.now();
            const distance = countDownDate - now;
            let days = Math.floor(distance / (1000 * 60 * 60 * 24));
            let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("demo").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

            if (distance < 0) {
                clearInterval(interval);
                document.getElementById("demo").innerHTML = "EXPIRED";
                sendDataToPHP();
            }
        }, 1000);

        function sendDataToPHP() {
            const data = <?= $jsonData; ?>;
            fetch('', { // Sending POST request to the same file
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `data=expired&id_product=${data.id_product}`
                })
                .then(response => response.text())
                .then(result => {
                    console.log(result);
                    alert("Countdown expired and data updated!");
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    });
</script>

<div id="demo"></div>