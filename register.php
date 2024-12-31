<?php
session_start(); 
$mysqli = new mysqli("localhost", "root", "", "car_rental");

if ($mysqli->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $mysqli->connect_error]);
    exit;
}

$first_name = $_POST["first-name"];
$last_name = $_POST["last-name"];
$email = $_POST["email"];
$confirm_email = $_POST["confirm-email"];
$password = $_POST["password"];
$confirm_password = $_POST["confirm-password"];
$address = $_POST["address"];
$dob = $_POST["dob"];
$phone = $_POST["phone"];
$extra_phone = $_POST["extra-phone"];  

$encryptedpass = md5($password);
$encryptedconpass = md5($confirm_password);

$q = "SELECT * FROM customer WHERE email = '$email'";
$result = $mysqli->query($q);

if ($result->num_rows > 0) {
    echo"<script>
            alert('Email already exists.'); 
            window.location.href='signup.html';
            </script>";
}

$query = "INSERT INTO customer (firstname, lastname, email, address, dob, password, phone_number, second_phone_number)
          VALUES ('$first_name', '$last_name', '$email', '$address', '$dob', '$encryptedpass', '$phone', '$extra_phone')";

if ($mysqli->query($query) === TRUE) {
    header("Location: mainlog.html");
} else {
    echo"<script>
    alert('Error occured.'); 
    window.location.href='signup.html';
    </script>";}

$mysqli->close();
?>