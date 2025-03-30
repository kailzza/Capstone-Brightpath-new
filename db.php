<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scholarship_finder_web_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>
