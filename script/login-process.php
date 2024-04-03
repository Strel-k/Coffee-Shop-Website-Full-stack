<?php
require_once "script/Database.php"; 

class LoginController {
    private $connection;

    public function __construct() {
        $database = new Database("localhost", "root", "", "coffee");
        $this->connection = $database->getConnection();
            }

    public function loginUser($username, $password) {
        $username = mysqli_real_escape_string($this->connection, $username);

        $login_query = "SELECT * FROM customeraccounts WHERE Username='$username'";
        $login_result = mysqli_query($this->connection, $login_query);

        if (!$login_result) {
            $error_message = "MySQL error: " . mysqli_error($this->connection);
        } else {
            if (mysqli_num_rows($login_result) == 1) {
                $user_row = mysqli_fetch_assoc($login_result);
                $stored_password = $user_row['Password'];

                if (password_verify($password, $stored_password)) {
                    $_SESSION['Accountid'] = $user_row['CustomerID'];
                    header("Location: ../index.php");
                    exit();
                } else {
                    $error_message = "Invalid password";
                }
            } else {
                $error_message = "Invalid username";
            }
        }

        return $error_message;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loginController = new LoginController();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $error_message = $loginController->loginUser($username, $password);
}

?>
