<?php
require_once "script/database.php"; 

$database = new Database("localhost", "root", "", "coffee");

$connection = $database->getConnection();


if (isset($_SESSION['Accountid'])) {
    $customerID = $_SESSION['Accountid'];
    $query = "SELECT IsAdmin FROM customeraccounts WHERE CustomerID = $customerID";
    $result = mysqli_query($connection, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $isAdmin = isset($row['IsAdmin']) && $row['IsAdmin'] == 1 ? true : false;
    }
} else {
    $isAdmin = false;
}
?>
    
    
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet"  href="css/store.css">

        <link rel="stylesheet"  href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/font.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <scriot src="js/jquery-3.7.1.min.js"></script>
        <scriot src="js/bootstrap.bundle.min.js"></script>

        <title>Coffee Shop</title>
    </head>
    <style></style>

    <body style="background-color: black;">
  

    <?php include "script/header.php";?>
            <div class="container">
             <div class="flex">
                <div class="image-with-text">

                     
                    <div class="Inner-text" > <h1 style="display:flex; text-align: center; padding:30px; font-weight:500;"> Elevate your Coffee Experience</h1><hr><br> <p> Never Forget to buy coffee again! We ship fresh coffee to your door so you can spend more time connecting with others!</p></div>
                    
                <img src="img/Coffee-Background.jpg" style="margin-right:10px;" height="800" width="1200">
                
             </div>
            </div>
            </div>
            <div class="container" style="background-color: white;">
                <br>
                <h1 style="text-align: center;"> Why Choose Us?</h1>
                <section class="section py-5">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="flex">
                                <figure>
                                    <div class="logo-container" style="width: 250px; margin-top:25px;">
                                        <img src="img/Delivery-Image.png" width="150" height="200" style="margin-left:40px;">
                                    </div>
                                    <span style="margin-top:10px;"> Your Favorite Coffee Delivered on-time!</span>
                                    <hr>

                                </figure>
                                <figure>
                                    <div class="logo-container" style="width: 250px;">
                                        <img src="img/Schedule-image.png" width="150" height="200" style="margin-left:110px;">
                                    </div>
                                    <span> Complete Control - get your Coffee on your Schedule!</span>
                                    <hr>
                                </figure>
                                <figure>
                                    <div class="logo-container" style="width: 250px;">
                                        <img src="img/Time-Image.png" width="150" height="200" style="margin-left:60px;">
                                    </div>
                                    <span> Coffee Shortages never again occur!</span>
                                    <hr>
                                </figure>
                                
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            </div>
            <br><br>
            <hr>
          
        </div>
                <br>
                <br>
                <hr style="color:white;"> 
                <span style="color:white; display: flex; text-align: center;"> © 2023 Apayawa Coffee Company. All rights reserved.</span>
                <br> <br>
                <footer> 
                    <ul class="social-links">
                        <li> <a href="#"> <img src="img/Facebook.png" alt="fb link"></a></li>
                        <li> <a href="#"> <img src="img/Email.png"> </a></li>
                        <li> <a href="#"> <img src="img/Phone_icon.png"></a></li>
                    </ul>
                </footer>
            </div>
            <?php if($isAdmin) {
         include "adminpanel.php"; 
    }
    ?>


    </body>
    </html>