<?php
class UserRegistration {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function registerUser($username, $password, $confirmPassword, $email, $phoneNumber) {
        if ($password !== $confirmPassword) {
            return "Passwords do not match";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $checkUsernameQuery = "SELECT * FROM customeraccounts WHERE Username='$username'";
            $checkUsernameResult = mysqli_query($this->connection, $checkUsernameQuery);

            if (mysqli_num_rows($checkUsernameResult) > 0) {
                return "Username already exists";
            } else {
                $insertCustomerQuery = "INSERT INTO customers (Name, Email, Phone) VALUES ('$username', '$email', '$phoneNumber')";
                mysqli_query($this->connection, $insertCustomerQuery);

                $customerId = mysqli_insert_id($this->connection);

                $insertAccountQuery = "INSERT INTO customeraccounts (CustomerID, Username, Password, IsAdmin) VALUES ('$customerId', '$username', '$hashedPassword', 0)";
                mysqli_query($this->connection, $insertAccountQuery);

                return true; 
            }
        }
    }
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "coffee";
$connection = new mysqli($servername, $username, $password, $dbname);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$userRegistration = new UserRegistration($connection);
$errorOrSuccessMessage = $userRegistration->registerUser($_POST['username'], $_POST['password'], $_POST['confirm_password'], $_POST['email'], $_POST['phone_number']);

if ($errorOrSuccessMessage === true) {
    header("Location: login.php");
    exit();
} else {
    $error_message = $errorOrSuccessMessage;
}
?>
