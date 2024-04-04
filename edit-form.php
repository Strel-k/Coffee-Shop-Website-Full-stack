<?php
include "script/database.php"; 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "coffee";

$connection = mysqli_connect($servername, $username, $password, $dbname);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
$error_message = "";
$customer_name = isset($_GET['customername']) ? urldecode($_GET['customername']) : '';

if(isset($_GET['customerid'])) {
    $customerid = $_GET['customerid'];

    if(isset($connection)) {
        $query = "SELECT c.*, ca.Password AS Password FROM customers c INNER JOIN customeraccounts ca ON c.CustomerID = ca.CustomerID WHERE c.CustomerID = $customerid";

        $result = mysqli_query($connection, $query);

        if($result && mysqli_num_rows($result) > 0) {
            $customer = mysqli_fetch_assoc($result);

            $customer_name = $customer['Name']; 
            $email = $customer['Email'];
            $phone_number = $customer['Phone'];
            $password = $customer['Password']; 
        } else {
            $error_message = "Customer not found.";
        }
    } else {
        $error_message = "Database connection not established.";
    }
} else {
    $error_message = "Customer ID not provided.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_phone_number = $_POST['phone_number'];
    $new_password = $_POST['password']; 

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $update_query = "UPDATE customers SET Name = '$new_username', Email = '$new_email', Phone = '$new_phone_number' WHERE CustomerID = $customerid";

    if (!empty($new_password)) {
        $update_query .= ";"; 
        $update_query .= "UPDATE customeraccounts SET Password = '$hashed_password' WHERE CustomerID = $customerid";
    }

    if (mysqli_multi_query($connection, $update_query)) {
        $error_message = "Customer details updated successfully.";
        header("Location: accounts.php");
        exit;
    } else {
        $error_message = "Error updating customer details: " . mysqli_error($connection);
    }
}

// Close the database connection properly
mysqli_close($connection);
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet"  href="css/login.css">
        <link rel="stylesheet" type="text/css" href="css/font.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <title>Edit Form</title>
    </head>
    <body style="background-color: black;">
    <?php include "script/header.php";?>
    <div class="container" style="background-color: white; width:30%; padding:45px;">
        <label style="display:flex; margin-right:5%;">Edit Form</label>

        <div class="login">
        <form method="POST" action="">
        <div class="input-container"><br>
        <input type="text" name="username" value="<?php echo isset($customer_name) ? $customer_name : ''; ?>" placeholder="Username" required>
        </div><br>
        <div class="input-container">
            <input type="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" placeholder="Email" required>
        </div><br>
        <div class="input-container">
            <input type="tel" name="phone_number" value="<?php echo isset($phone_number) ? $phone_number : ''; ?>" placeholder="Phone Number" required>
        </div><br>
        <div class="input-container">
            <input type="password" name="password" placeholder="New Password">
        </div><br>
        <button style="border-radius:10px; margin-left:23%; padding:10px; width:50%;">Update</button>
    </form>
        </div><br>
        <p style="color: red;"><?php echo $error_message; ?></p>
        <a href="accounts.php"><p>Back to Accounts</p></a>
    </div>
    <?php include "script/footer.php";?>
    <?php if($isAdmin) {
            include "adminpanel.php"; 
        }
        ?>
    </body>
    </html>
