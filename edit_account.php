<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "admin_panel");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the admin ID from the URL
$admin_id = $_GET['id'];

// SQL query to retrieve admin data
$sql = "SELECT * FROM admins WHERE admin_id = '$admin_id'";
$result = $conn->query($sql);

// Check if admin exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    ?>
    <form action="" method="post">
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" value="<?php echo $row['full_name']; ?>"><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>"><br><br>
        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" value="<?php echo $row['phone']; ?>"><br><br>
        <input type="submit" name="update" value="Update">
    </form>
    <?php
} else {
    echo "Admin not found.";
}

// Update admin data
if (isset($_POST['update'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "UPDATE admins SET full_name = '$full_name', email = '$email', phone = '$phone' WHERE admin_id = '$admin_id'";
    $conn->query($sql);

    // Redirect to manage_accounts.php
    echo "<script>window.location.href='manage_account.php';</script>";
    exit;
}

$conn->close();
?>