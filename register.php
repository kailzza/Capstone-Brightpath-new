<?php
include 'db.php';
session_start();

// Define the target directory for uploaded files
$targetDir = "uploads/"; // Ensure this directory exists

// Check if the uploads directory exists, if not create it
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

if (isset($_POST['register'])) {
    // Personal Information
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $job_title = $_POST['job_title'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Organization Details
    $organization_name = $_POST['organization_name'];
    $organization_type = $_POST['organization_type'];
    $office_address = $_POST['office_address'];
    $province_district = $_POST['province_district'];
    $official_email = $_POST['official_email'];
    $website = $_POST['website'];

    // Authorization & Verification
    $authLetterName = basename($_FILES["auth_letter"]["name"]);
    $authLetterPath = $targetDir . $authLetterName; // Correct path
    $idProofName = basename($_FILES["id_proof"]["name"]);
    $idProofPath = $targetDir . $idProofName; // Correct path

    // Move uploaded files
    if (!move_uploaded_file($_FILES["auth_letter"]["tmp_name"], $authLetterPath) || 
        !move_uploaded_file($_FILES["id_proof"]["tmp_name"], $idProofPath)) {
        echo "Error uploading files.";
        exit;
    }

    // Check if email exists
    $check = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($check);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email already registered. Try another one.";
    } else {
        // Get the User_type_id for 'admin' from the user_types table
        $sql_user_type = "SELECT User_type_id FROM user_types WHERE User_type = 'admin'";
        $result_user_type = $conn->query($sql_user_type);

        if ($result_user_type->num_rows > 0) {
            $user_type_row = $result_user_type->fetch_assoc();
            $user_type_id = $user_type_row['User_type_id'];
        } else {
            // If 'admin' user type is not found, create a new record
            $sql_create_user_type = "INSERT INTO user_types (User_type) VALUES ('admin')";
            $conn->query($sql_create_user_type);
            $user_type_id = $conn->insert_id;
        }

        // Insert into Users Table
        $sql_user = "INSERT INTO users (first_name, middle_name, last_name, email, phone, password, User_type_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_user);
        $stmt->bind_param("ssssssi", $first_name, $middle_name, $last_name, $email, $phone, $password, $user_type_id);

        if ($stmt->execute()) {
            $user_id = $conn->insert_id;

            // Insert into User Profiles Table
            $sql_profile = "INSERT INTO user_profiles (user_id) VALUES (?)";
            $stmt = $conn->prepare($sql_profile);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            // Insert into Admin Organizations Table
            $sql_org = "INSERT INTO admin_organizations (user_id, organization_name, organization_type, office_address, province_district, official_email, website) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_org);
            $stmt->bind_param("issssss", $user_id, $organization_name, $organization_type, $office_address, $province_district, $official_email, $website);
            $stmt->execute();

            // Insert into Admin Authorizations Table
            $sql_auth = "INSERT INTO admin_authorizations (user_id, authorization_letter, id_proof) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql_auth);
            $stmt->bind_param("iss", $user_id, $authLetterPath, $idProofPath);
            $stmt->execute();

            // Display a message instead of redirecting
            echo "<script>alert('Your account is being requested. Wait for approval.');</script>";
        } else {
            echo "Error registering user.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
    <link rel="stylesheet" href="register_style.css">
</head>
<body>
    <form method="POST" action="register.php" enctype="multipart/form-data" id="register-form">
        <!-- Slide 1 -->
        <div class="slide active" id="slide-1">
            <h2>1/3 Personal Information</h2>
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" placeholder="First Name" required>
            <label for="middle_name">Middle Name</label>
            <input type="text" name="middle_name" placeholder="Middle Name" required>
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="Email" required><br>
            <label for="phone">Phone number</label>
            <input type="text" name="phone" placeholder="Phone Number" required><br>
            <label for="job_title">Job Title</label>
            <input type="text" name="job_title" placeholder="Job Title/Position" required><br>
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Password" required><br>
            <button style="padding: 10px 20px;border: none;border-radius: 5px;color:white;background-color:white;cursor:arrow;" type="button" onclick="showPrevSlide()">Previous</button>
            <button class="next-btn" type="button" onclick="showNextSlide()">Next</button>
        </div>
        <!-- Slide 2 -->
        <div class="slide" id="slide-2">
            <h2>2/3 Organization Details</h2>
            <input type="text" name="organization_name" placeholder="Organization Name" required><br>
            <select name="organization_type" required>
                <option value="Government">Government</option>
                <option value="Private">Private</option>
                <option value="NGO">NGO</option>
                <option value="Other">Other</option>
            </select><br>
            <textarea name="office_address" placeholder="Office Address" required></textarea><br>
            <input type="text" name="province_district" placeholder="Province/District" required><br>
            <input type="email" name="official_email" placeholder="Official Email" required><br>
            <input type="text" name="website" placeholder="Website (if applicable)"><br>
            <button class="prev-btn" type="button" onclick="showPrevSlide()">Previous</button>
            <button class="next-btn" type="button" onclick="showNextSlide()">Next</button>
        </div>
        <!-- Slide 3 -->
        <div class="slide" id="slide-3">
            <h2>3/3 Authorization & Verification</h2>
            <label>Upload Authorization Letter (PDF/Image):</label><br>
            <input type="file" name="auth_letter" required><br>
            <label>Upload ID Proof (PDF/Image):</label><br>
            <input type="file" name="id_proof" required><br>
            <button class="prev-btn" type="button" onclick="showPrevSlide()">Previous</button>
            <button class="submit-btn" type="submit" name="register">Register</button>
        </div>
    </form>
    <script>
        var currentSlide = 1;
        var totalSlides = 3;

        function showNextSlide() {
            if (currentSlide < totalSlides) {
                document.getElementById('slide-' + currentSlide).classList.remove('active');
                currentSlide++;
                document.getElementById('slide-' + currentSlide).classList.add('active');
            }
        }

        function showPrevSlide() {
            if (currentSlide > 1) {
                document.getElementById('slide-' + currentSlide).classList.remove('active');
                currentSlide--;
                document.getElementById('slide-' + currentSlide).classList.add('active');
            }
        }
    </script>
    <p style="color: white; margin-top: -20px;">Already have an account? <a href="login.php">Login</a></p>
</body>
</html>