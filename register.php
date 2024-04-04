<?php

include "script/database.php";

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "script/register-process.php";
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"  href="css/login.css">
        <link rel="stylesheet" type="text/css" href="css/font.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <title>Login</title>
</head>
<body style="background-color: black;">
<?php include "script/header.php";?>
    <div class="container" style="background-color: white; width:60%; padding:45px;">
    <label style="display:flex; margin-right:5%;">Registration Form</label>

    <div class="login">
    <form method="POST" action="">
            <div class="input-container"><br>
                <input type="text" name="username" placeholder="Username" required>
            </div><br>
            <div class="input-container">
                <input type="email" name="email" placeholder="Email" required>
            </div><br>
            <div class="input-container">
                <input type="tel" name="phone_number" placeholder="Phone Number" required>
            </div><br>
            <div class="input-container">
                <input type="password" name="password" placeholder="Password" required>
            </div><br>
            <div class="input-container">
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            </div><br>
            <button style="border-radius:10px; margin-left:23%; padding:10px; width:50%;">Register</button>
        </form>
    </div><br>
    <p style="color: red;"><?php echo $error_message; ?></p> <!-- Display error message -->
    <a href="login.php"><p>Already have an Account?</p></a> <!-- Link to login page -->
    </div> have an Account?</p></a> 
    </div>
    <?php include "script/footer.php";?>
    <?php if($isAdmin) {
         include "adminpanel.php"; 
    }
    ?>
     <?php if($isAdmin) {
         include "adminpanel.php"; 
    }
    ?>
</body>
</html>