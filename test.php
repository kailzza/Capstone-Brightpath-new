<?php
include 'db.php';
session_start();

if (isset($_POST['register'])) {
    // Personal Information
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $job_title = $_POST['job_title'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password

    // Organization Details
    $organization_name = $_POST['organization_name'];
    $organization_type = $_POST['organization_type'];
    $office_address = $_POST['office_address'];
    $province_district = $_POST['province_district'];
    $contact_number = $_POST['contact_number'];
    $official_email = $_POST['official_email'];
    $website = $_POST['website'];

    // File Uploads
    $targetDir = "uploads/";

    // Ensure upload directory exists
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Upload Authorization Letter
    $authLetterName = basename($_FILES["auth_letter"]["name"]);
    $authLetterPath = $targetDir . $authLetterName;
    move_uploaded_file($_FILES["auth_letter"]["tmp_name"], $authLetterPath);

    // Upload ID Proof
    $idProofName = basename($_FILES["id_proof"]["name"]);
    $idProofPath = $targetDir . $idProofName;
    move_uploaded_file($_FILES["id_proof"]["tmp_name"], $idProofPath);

    // Check if email exists
    $check = "SELECT * FROM admins WHERE email=?";
    $stmt = $conn->prepare($check);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email already registered. Try another one.";
    } else {
        // Insert into Admins Table
        $sql_admin = "INSERT INTO admins (full_name, email, phone, job_title, password) 
                      VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_admin);
        $stmt->bind_param("sssss", $full_name, $email, $phone, $job_title, $password);
        $stmt->execute();
        $admin_id = $conn->insert_id;

        // Insert into Organizations Table
        $sql_org = "INSERT INTO admins_organizations (admin_id, organization_name, organization_type, office_address, province_district, contact_number, official_email, website) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_org);
        $stmt->bind_param("isssssss", $admin_id, $organization_name, $organization_type, $office_address, $province_district, $contact_number, $official_email, $website);
        $stmt->execute();

        // Insert into Authorizations Table
        $sql_auth = "INSERT INTO admins_authorizations (admin_id, authorization_letter, id_proof) 
                     VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql_auth);
        $stmt->bind_param("iss", $admin_id, $authLetterPath, $idProofPath);
        $stmt->execute();

        echo "Registration successful. <a href='login.php'>Login here</a>";
    }
}
?><!DOCTYPE html>
<html>
<head>
  <title>Registration Form</title>
  <style>

    .slide {
      width: 80%;
      margin: 40px auto;
      padding: 20px;
      background-color:lightblue;
      border: 1px solid #ccc;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      display: none;
      max-width: 500px;
    }
    .slide.active {
      display: block;
    }
    .next-btn, .prev-btn, .submit-btn {
      background-color:rgb(78, 76, 175);
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .next-btn:hover, .prev-btn:hover, .submit-btn:hover {
      background-color:rgb(49, 48, 105);
    }

     h2{
        color:rgb(26, 24, 31);
        text-align: center;
        display: block;
    }

    
    input{
        display: block;
        margin-left: auto;
        margin-right: auto;
       width: 70%;
        margin-block: 5px;
        border-radius: 5px;
        border:  0px ;
        padding: 10px;
    }
    .next-btn{
      float: right;
      margin-right: 10px;
      
    }
  </style>
</head>
<body>
  <form  method="POST" enctype="multipart/form-data" id="register-form">
    <!-- Slide 1 -->
    <div class="slide active" id="slide-1">
      <h2>1. Personal Information</h2> <input type="text" name="full_name" placeholder="Full Name" required><br>
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="text" name="phone" placeholder="Phone Number" required><br>
        <input type="text" name="job_title" placeholder="Job Title/Position" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button  style="padding: 10px 20px;border: none;border-radius: 5px;color:lightblue;background-color:lightblue;cursor:arrow;" type="button" onclick="showPrevSlide()">Previous</button>
      <button class="next-btn" type="button" onclick="showNextSlide()">Next</button>
    </div>

    <!-- Slide 2 -->
    <div class="slide" id="slide-2">
      <h2>2. Organization Details</h2>
        <input type="text" name="organization_name" placeholder="Organization Name" required><br>
        <select name="organization_type" required>
            <option value="Government">Government</option>
            <option value="Private">Private</option>
            <option value="NGO">NGO</option>
            <option value="Other">Other</option>
        </select><br>
        <textarea name="office_address" placeholder="Office Address" required></textarea><br>
        <input type="text" name="province_district" placeholder="Province/District" required><br>
        <input type="text" name="contact_number" placeholder="Contact Number" required><br>
        <input type="email" name="official_email" placeholder="Official Email" required><br>
        <input type="text" name="website" placeholder="Website (if applicable)"><br>
        <button class="prev-btn" type="button" onclick="showPrevSlide()">Previous</button>
        <button class="next-btn" type="button" onclick="showNextSlide()">Next</button>
    </div>

    <!-- Slide 3 -->
    <div class="slide" id="slide-3">
      <h2>3. Authorization & Verification</h2>   
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
</body>
</html>
