<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "admin_panel");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the admin ID from the URL
$admin_id = $_GET['id'];

// SQL query to archive admin data
$sql = "UPDATE admins SET archived = 1 WHERE admin_id = '$admin_id'";
$conn->query($sql);

header("Location: manage_accounts.php");
exit;

$conn->close();
?>
