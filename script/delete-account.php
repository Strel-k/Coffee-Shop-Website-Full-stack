<?php
session_start();
require_once "database.php"; 

error_reporting(E_ALL);
ini_set('display_errors', 1);

class AdminController {
    private $database;

    public function __construct(Database $database) {
        $this->database = $database;
    }

    public function deleteCustomer($customer_id) {
        if (!isset($_SESSION['IsAdmin']) || $_SESSION['IsAdmin'] != 1) {
            header("Location: login.php");
            exit;
        }

        if (!empty($customer_id)) {
            $customer_id = intval($customer_id);

            $connection = $this->database->getConnection();

            $delete_customer_accounts_query = "DELETE FROM customeraccounts WHERE CustomerID = ?";
            $delete_customer_accounts_stmt = $connection->prepare($delete_customer_accounts_query);
            $delete_customer_accounts_stmt->bind_param("i", $customer_id);
            $delete_customer_accounts_stmt->execute();

            if ($delete_customer_accounts_stmt->affected_rows > 0) {
                $delete_customer_query = "DELETE FROM customers WHERE CustomerID = ?";
                $delete_customer_stmt = $connection->prepare($delete_customer_query);
                $delete_customer_stmt->bind_param("i", $customer_id);
                $delete_customer_stmt->execute();

                if ($delete_customer_stmt->affected_rows > 0) {
                    echo "<script>alert('Customer Deleted!');</script>";
                    header("Location: ../accounts.php");
                } else {
                    $response = array(
                        "status" => "error",
                        "message" => "Failed to delete customer."
                    );
                }

                $delete_customer_stmt->close();
            } else {
                $response = array(
                    "status" => "error",
                    "message" => "Failed to delete related customer accounts."
                );
            }

            $delete_customer_accounts_stmt->close();

            $connection->close();

            echo json_encode($response);
        } else {
            echo "Error: No customer ID provided.";
            exit;
        }
    }
}

$adminController = new AdminController(new Database("localhost", "root", "", "coffee"));
$adminController->deleteCustomer($_GET['customer_id'] ?? null);
?>
