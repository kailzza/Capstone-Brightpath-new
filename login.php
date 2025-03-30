<?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['admin_id'] = $row['admin_id'];
        $_SESSION['full_name'] = $row['full_name'];
        header("Location: manage_account_admin.php");
    } else {
        echo "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<style>
    body{
        background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('backgroundpic.png');
        background-size: 100% 100%, cover; 
        background-repeat: no-repeat;
        height: 100vh;
    }
   
    h2{
        text-align: center;
        display: block;
    }
    .outer{
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .container{
        margin-top: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        
       
    }
    form{
        
        border-radius: 15px;
        background-color: rgb(255, 255, 255);
        padding: 20px;
        width: 300px;


    }
    input{
        display: block;
        margin-left: auto;
        margin-right: auto;
       width: 70%;
        margin-block: 5px;
        border-radius: 5px;
        border:  solid lightgray 2px ;
        padding: 10px;
       
        box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.2);
    }
    
label{
    margin-left: 20px;
}
    button{
        margin-top: 10px;
        display: block;
        margin-left: auto;
        margin-right: auto;
       width: 80%;
       border:  0px ;
       padding: 10px;
        border-radius: 5px;
        font-weight: 800;
       
      background-color:rgb(78, 76, 175);
      color: #fff;
        transition: background-color 2s ease-in-out;
        transition: color 0.5s ease-in-out;
    }
    
    button:hover{
        cursor: pointer;
        background-color:rgb(49, 48, 105);
        color: white;
    }
    h2,p{
        text-align: center;
        display: block;
    }
    a{
       
        font-weight: 800;
    }
</style>
<body>
  
    <div class="outer">
        
    <div class="container">
   
    <form method="POST">
    <h2>Admin Login</h2>
       <label for="email">Email</label>
        <input type="email" name="email" placeholder="Email" required><br>
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="login">Login</button>
        
    <p>Don't have an account? <a href="register.php">Sign Up</a></p>

</form>
    </div>
    </div>
    
</body>
</html>
