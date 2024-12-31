<?php

$mysqli = new mysqli("localhost", "root", "", "car_rental");


if ($mysqli->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed."]);
    exit();
}


$email = $_POST["email"];
$password = $_POST["password"];
$encryptedpassword=md5($password);


$q="SELECT * FROM customer WHERE email = '$email' and password='$encryptedpassword'";
$result = $mysqli->query($q);
if($result->num_rows>0)
{ 
$user = $result->fetch_assoc();
header("Location: mainlog.html");
//echo $q;
}
else
{
    echo"<script>
    alert('Incorrect email or password.'); 
    window.location.href='login.html';
    </script>";}


?>







