<?php 
//connection into function
require 'function.php';

//start the session
session_start();

//insert value to variable username
$username = $_SESSION['username'];

//check if user doesn't login
if(!isset($username) ) {
    //direct into login page 
    header("Location: login.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LelangIN | Account</title>

    <!-- Bootstrap -->

    <!-- CSs -->
</head>
<body>
<h1 class="title">Account Page</h1>

<?php 
//open database
$db = new database();

//open the query

//inner join from table 
$sql = "SELECT tb_account.username FROM `tb_account` JOIN tb_address ON tb_address.id_user = tb_account.id_user";
// var_dump($sql);
// exit();

$query = $db->getConnection()->prepare("SELECT * FROM `tb_account` WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();

//get result with diff variable 
$data_user = $query->get_result();
$data_user = $data_user->fetch_assoc();

if($data_user) {
?>
 <h1><?=  $data?></h1>
<?php 
    }else {

?>

<?php 
}

?>
    
</body>
</html>