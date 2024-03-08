<?php 
$connection = mysqli_connect("localhost","root","","db_lelang");

if(!$connection) {
   echo"<script>
    alert('database isn't connect');
   </script> ";
}

?>