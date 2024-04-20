<?php

require '../admin/function.php';

$db = new database();

//set time to asia jakarta
date_default_timezone_set('Asia/Jakarta');

$query = $db->getConnection()->query("SELECT * FROM tb_countdown ORDER BY id DESC LIMIT 1");
$data = $query->fetch_assoc();

$date_now = date("Y:m:d");

if ($data !== null) {
    $datetime = $data['date'] . " " . $data['hour'] . ":" . $data['minute'] . ":" . $data['second'];
    $datetime = strtotime($datetime);
}

?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let countDownDate = <?= $datetime ?>;
        let now = <?= time() * 1000 ?>;
        console.log("Countdown Date: " + new Date(countDownDate));
        console.log("Now: " + new Date(now));

        let x = setInterval(function() {
            now = Date.now();
            const distance = countDownDate - now;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("demo").innerHTML = days + "d " + hours + "h " +
                minutes + "m " + seconds + "s ";

            if (distance < 0) {
                clearInterval(x);
                document.getElementById("demo").innerHTML = "EXPIRED";
                sendDataToPHP();
            }
        }, 1000);
    });


    function sendDataToPHP() {
        var id_product = document.getElementById("uniqueIdProduct").value;
        console.log(id_product);

        fetch('ajax_timer.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `data=expired&id_product=${id_product}`,
            })
            .then(response => response.text())
            .then(result => {
                console.log("Response from PHP: " + result);
                // if (result.includes("berhasil")) {   
                //     alert("Lelang telah berakhir dan diperbarui.");
                // }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
</script>