<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "coffee";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

class UserLogin {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function authenticateUser($username, $password) {
        $login_query = "SELECT * FROM customeraccounts WHERE Username=?";
        $stmt = mysqli_prepare($this->connection, $login_query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $login_result = mysqli_stmt_get_result($stmt);

        if (!$login_result) {
            return "MySQL error: " . mysqli_error($this->connection);
        } else {
            if (mysqli_num_rows($login_result) == 1) {
                $user_row = mysqli_fetch_assoc($login_result);
                $stored_password = $user_row['Password'];

                if (password_verify($password, $stored_password)) {
                    session_start();
                    $_SESSION['Accountid'] = $user_row['CustomerID'];

                    header("Location: index.php");
                    exit();
                } else {
                    return "Invalid password";
                }
            } else {
                return "Invalid username";
            }
        }
    }
}

$userLogin = new UserLogin($conn);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $errorOrSuccessMessage = $userLogin->authenticateUser($username, $password);

    if (!empty($errorOrSuccessMessage)) {
        $error_message = $errorOrSuccessMessage;
    }
}

$conn->close();
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
        <label style="display:flex; margin-right:5%;">Login Form</label>

        <div class="login">
            <form method="POST" action="">
                <div class="input-container"><br>
                    <input type="text" name="username" placeholder="Username" required>
                </div><br>
                <div class="input-container">
                    <input type="password" name="password" placeholder="******" required autocomplete="current-password">
                </div><br>
                <button style="border-radius:10px; margin-left:23%; padding:10px; width:50%;">Login</button>
            </form>
        </div><br>
        <?php if (isset($error_message)) { ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php } ?>
        <a href="register.php"><p>Don't have an Account?</p></a> 
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
