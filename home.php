<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

header {
    padding: 0px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: bolder;
    border-bottom: solid lightgrey 1px;
    height: 50px;
    margin-left: 250px;
}
.logo {
   display: flex; 
    height: 38px;
    width: 38px;
    border: solid lightgrey 2px;
    
  
    border-radius:50%;
}
.logo:hover{
   
    border: solid lightblue 3px;
}
.logoimg {
    border-radius:50%;
  

}

.profile {
    display: flex;
    align-items: center;
    margin-right: 20px;
   

}
.profile span{
    font-weight: bolder;
    color:rgb(27, 77, 92);
    font-size: 25px;

}

.profile img {
    width: 50px;
    height: 50px;
    border-radius: 50% 50% 50% 0%;
    margin-right: 10px;
}

.logout {
   
    background-color:white;
    margin-right: 50px;
    border-radius: 10px;
}

.logout a.lo {
    color: black;
    padding-inline: 15px;
    text-decoration: none;
  
}
.logout a img {
    margin-top: 10px;

}
.logout a.lo:hover{
    border-bottom: solid black 3px; 
   
}

aside {
    background-color: lightblue;
    width: 250px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    padding: 20px;
    box-sizing: border-box;
}

nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    margin-top: 50px;
  
}

nav li {
  
    text-align: center;
    margin-bottom: 10px;
    padding-block: 8px;

}

nav a {
   padding-block: 8px;

    text-align: center;
    color: #333;
    text-decoration: none;
    font-weight: bold;

}
nav li:hover{
    text-align: center;
    background-color:  rgb(255, 255, 255);
    font-weight: bolder;

}

main {
    margin-left: 250px;
    padding: 20px;
}

main h1 {
    margin-top: 0;
}
img .profile1{
    height: 25px;
}
</style>
<body>
    <header>
        <div class="lgogo">
            
        </div>
        <div class="logout">
        
        <a class="lodd"  alt="Logo">
        <img class="logoimg" src="profile.png.jpg" width="33" height="33" alt="Logo">
        </a> 
           
            <a class="lo"href="logout.php">Logout</a> 
        
        
            
       
        </div>
        
    </header>
    <aside>
        
    <div class="profile">
            <img src="logo.jpg" alt="Profile Picture">
            <span>BrightPath</span>
        </div>
        <nav>
            <ul>
                <li><a href="                     
                <li><a href="#">Messages</a></li>
                <li><a href="                    
                <li><a href="#">Announcement</a></li>
                <li><a href="                    
                <li><a href="#">Help & FAQs </a></li>
                <li><a href="                    
                <li><a href="#">Saved</a></li>
                <li><a href="                    
                <li><a href="#">Settings</a></li>
                <li><a href="                    
                <li><a href="#">Logout</a></li>
            </ul>
        </nav>
    </aside>
    <main>
        <!-- Main content goes here -->
        <h1>Dashboard</h1>
        <p>Welcome to the admin panel.</p>
    </main>
</body>
</html>


