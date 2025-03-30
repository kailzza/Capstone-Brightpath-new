<?php
$currentPage = 'admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    
    <link rel="stylesheet" href="style.css">
</head>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }
    .container {
        width: 80%;
        margin: 40px auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
        text-align: center;
        color: #333;
    }
    .toolbar {
        margin-top: -50px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-left: 30px;
        margin-right: 60px;
    }
    .toolbar button {
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 14px; /* Adjusted font size */
    }
    .toolbar button:hover {
        background-color: #0056b3;
    }
    .search-form, .filter-form, .sort-form {
        display: inline-block;
        margin-right: 10px;
    }
    .search-form input[type="text"],
    .filter-form select,
    .sort-form select {
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-right: 5px;
        width: auto; /* Allow inputs to fit without wrapping */
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }
    th {
        background-color: #007bff;
        color: #ffffff;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    tr:hover {
        background-color: #e9ecef;
    }
    .popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        padding: 20px;
        border: 1px solid #ddd;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        display: none;
    }
    .popup-content {
        max-width: 500px;
        margin: 0 auto;
    }
    .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 20px;
        cursor: pointer;
    }
    .close-btn:hover {
        color: #ccc;
    }
    label {
        display: block;
        margin-bottom: 10px;
    }
    input[type="text"], input[type="email"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
    }
    input[type="submit"] {
        background-color: #4CAF50;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        background-color: #3e8e41;
    }
</style>

<body>
<?php
include 'header_sidebar.html';
?>

<main>
  
<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "scholarship_finder_web_system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize SQL query
$sql = "SELECT * FROM users";

// Search functionality
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
    $sql .= " WHERE first_name LIKE '%$searchTerm%' OR last_name LIKE '%$searchTerm%'";
}

// Filter functionality
if (isset($_GET['filter']) && !empty($_GET['filter'])) {
    $filter = mysqli_real_escape_string($conn, $_GET['filter']);
    $sql .= (strpos($sql, 'WHERE') !== false ? " AND" : " WHERE") . " role = '$filter'";
}

// Sort functionality
if (isset($_GET['sort']) && !empty($_GET['sort'])) {
    $sort = mysqli_real_escape_string($conn, $_GET['sort']);
    $sql .= " ORDER BY $sort";
}

// Execute query
$result = $conn->query($sql);

// Display toolbar with search, filter, and sort options
echo "<div class='toolbar'>";
echo "<div class='search-form'>
        <form method='GET' style='display: inline;'>
            <input type='text' name='search' placeholder='Search...'>
            <button type='submit'>Search</button>
        </form>
      </div>";
echo "<div class='filter-form'>
        <form method='GET' style='display: inline;'>
            <select name='filter'>
                <option value=''>Filter by...</option>
                <option value='role1'>Role 1</option>
                <option value='role2'>Role 2</option>
                <option value='role3'>Role 3</option>
            </select>
            <button type='submit'>Filter</button>
        </form>
      </div>";
echo "<div class='sort-form'>
        <form method='GET' style='display: inline;'>
            <select name='sort'>
                <option value=''>Sort by...</option>
                <option value='first_name'>First Name</option>
                <option value='last_name'>Last Name</option>
                <option value='email'>Email</option>
            </select>
            <button type='submit'>Sort</button>
        </form>
      </div>";
echo "<button class='add-new-button' onclick=\"window.location.href='add_user.php'\">Add New User</button>";
echo "</div>";

// Display data
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>id</th><th>First Name</th><th>Middle Name</th><th>Last Name</th><th>Email</th><th>Phone Number</th><th>Actions</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["user_id"] . "</td>";
        echo "<td>" . $row["first_name"] . "</td>";
        echo "<td>" . $row["middle_name"] . "</td>";
        echo "<td>" . $row["last_name"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["phone"] . "</td>";  // Ensure 'phone' is included in your query
        // Corrected data attributes for the edit button
        echo "<td>
                <button class='edit-btn' 
                        data-id='" . $row["user_id"] . "' 
                        data-first_name='" . $row["first_name"] . "' 
                        data-middle_name='" . $row["middle_name"] . "' 
                        data-last_name='" . $row["last_name"] . "' 
                        data-email='" . $row["email"] . "' 
                        data-phone='" . $row["phone"] . "'>Edit</button> | 
                <button class='archive-btn' data-id='" . $row["user_id"] . "'>Archive</button>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No results";
}

// Popup editing form
echo "<div class='popup' id='popup' style='display: none;'>";
echo "<div class='popup-content'>";
echo "<span class='close-btn'>&times;</span>";
echo "<h2>Edit Account</h2>";
echo "<form action='' method='post'>";
echo "<label for='first_name'>First Name:</label>";
echo "<input type='text' id='first_name' name='first_name'>";
echo "<label for='middle_name'>Middle Name:</label>";
echo "<input type='text' id='middle_name' name='middle_name'>";
echo "<label for='last_name'>Last Name:</label>";
echo "<input type='text' id='last_name' name='last_name'>";
echo "<label for='email'>Email:</label>";
echo "<input type='email' id='email' name='email'>";
echo "<label for='phone'>Phone Number:</label>";
echo "<input type='text' id='phone' name='phone'>";
echo "<input type='hidden' name='user_id' id='user_id'>";
echo "<input type='submit' name='update' value='Update'>";
echo "</form>";
echo "</div>";
echo "</div>";

// JavaScript code for popup
echo "<script>";
echo "const editBtns = document.querySelectorAll('.edit-btn');";
echo "const popup = document.getElementById('popup');";
echo "const closeBtn = document.querySelector('.close-btn');";
echo "editBtns.forEach(btn => {";
echo "btn.addEventListener('click', () => {";
echo "const userId = btn.getAttribute('data-id');";
echo "const first_name = btn.getAttribute('data-first_name');";
echo "const middle_name = btn.getAttribute('data-middle_name');";
echo "const last_name = btn.getAttribute('data-last_name');";
echo "const email = btn.getAttribute('data-email');";
echo "const phone = btn.getAttribute('data-phone');";
echo "document.getElementById('user_id').value = userId;";
echo "document.getElementById('first_name').value = first_name;";
echo "document.getElementById('middle_name').value = middle_name;";
echo "document.getElementById('last_name').value = last_name;";
echo "document.getElementById('email').value = email;";
echo "document.getElementById('phone').value = phone;";
echo "popup.style.display = 'block';";
echo "});";
echo "});";
echo "closeBtn.addEventListener('click', () => {";
echo "popup.style.display = 'none';";
echo "});";
echo "</script>";

// Update admin data
if (isset($_POST['update'])) {
    $user_id = $_POST['user_id']; // Changed from $admin_id to $user_id to match the input field
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "UPDATE users SET first_name = '$first_name', middle_name = '$middle_name', last_name = '$last_name', email = '$email', phone = '$phone' WHERE user_id = '$user_id'";
    $conn->query($sql);

    echo "<script>window.location.href='manage_account_admin.php';</script>";
    exit;
}

$conn->close();
?>

</main>

</body>
</html>