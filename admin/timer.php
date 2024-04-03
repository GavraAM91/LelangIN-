<?php 

include 'function.php';

// $obj = new timer;
// $data = $obj->showTime();    

// $dateString = $row['date'] . ' ' . $row['hour'] . ':' . $row['minute'] . ':' . $row['second'];

$db = new database();

$query = $db->getConnection()->query("SELECT * FROM tb_countdown ORDER BY id DESC LIMIT 1");
$data = $query->fetch_assoc();

?>

<script>

 var countDownDate = <?= strtotime($data['date'] . "-" . $data['hour'] . ":" . $data['minute'] . ":" . $data['second'] ) * 1000 ;?>;
 document.addEventListener('DOMContentLoaded', function() {
    var now = <?= time() ?> * 1000;

    // Update the countdown every 1 second
    var x = setInterval(function() {
        now = now + 1000;
        var distance = countDownDate - now;

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("demo").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

        if (distance < 0) {
            clearInterval(x);
            document.getElementById("demo").innerHTML = "EXPIRED";
        }
    }, 1000);
});

</script>
