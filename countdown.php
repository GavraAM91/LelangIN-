<?php
header('Content-Type: application/javascript');
date_default_timezone_set("Asia/Jakarta");
$now = new DateTime();
$target = new DateTime();
$target->add(new DateInterval('PT1H')); // Menambahkan 1 jam
$js_target = $target->format('M d, Y H:i:s');
?>

// Mengatur waktu target untuk countdown
var countDownDate = new Date("<?php echo $js_target; ?>").getTime();

// Update countdown setiap 1 detik
var x = setInterval(function() {
    // Dapatkan waktu sekarang
    var now = new Date().getTime();
    
    // Temukan jarak antara sekarang dan waktu target
    var distance = countDownDate - now;
    
    // Perhitungan waktu untuk hari, jam, menit dan detik
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    // Tampilkan hasil di elemen dengan id="timer"
    document.getElementById("timer").innerHTML = hours + "h "
    + minutes + "m " + seconds + "s ";
    
    // Jika countdown selesai, tampilkan teks
    if (distance < 0) {
        clearInterval(x);
        document.getElementById("timer").innerHTML = "EXPIRED";
    }
}, 1000);

