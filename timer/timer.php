<?php

include '../admin/function.php';

$db = new database();

//set time to asia jakarta
date_default_timezone_set('Asia/Jakarta');

$query = $db->getConnection()->query("SELECT * FROM tb_countdown ORDER BY id DESC LIMIT 1");
$data = $query->fetch_assoc();

if($data !== null) {
$jsonData = json_encode([
    'id_product' => $data['id_product']
]);
}

// var_dump($data['id_product']);

if ($data !== null) {
    $datetime = $data['date'] . " " . $data['hour'] . ":" . $data['minute'] . ":" . $data['second'];
    $datetime = strtotime($datetime);
}

?>



<script>
    //receive data from form
    document.addEventListener('DOMContentLoaded', function() {
        let countDownDate = <?= $datetime * 1000; ?>;
        let now = <?= time() * 1000 ?>;
        console.log("Countdown Date: " + new Date(countDownDate));
        console.log("Now: " + new Date(now));


        const x = setInterval(function() {
            //  var now = now + 1000;
            now = Date.now();
            const distance = countDownDate - now;
            // if (distance >= 0) {
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            //display the result in the element with id = "demo"
            document.getElementById("demo").innerHTML = days + "d " + hours + "h " +
                minutes + "m " + seconds + "s ";
            // } 

            if (distance < 0) {
                clearInterval(x);
                document.getElementById("demo").innerHTML = "EXPIRED";
                sendDataToPHP();
            }
        }, 1000);
    });

    function sendDataToPHP() {
    const data = <?php echo $jsonData; ?>; // data is already an object
    const productId = data.id_product; // Access the id_product property directly

    console.log(`Product ID: ${productId}`);

    // Assuming 'productId' contains the correct value, send it to your PHP script
    fetch('ajax_timer.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `data=expired&id_product=${productId}`,
        })
        .then(response => response.text())
        .then(result => {
            console.log(result);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

</script>