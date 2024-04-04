<?php
session_start();
require_once "database.php"; 

error_reporting(E_ALL);
ini_set('display_errors', 1);

class AdminOrderController {
    private $database;

    public function __construct(Database $database) {
        $this->database = $database;
    }

    public function deleteOrder($order_id) {
        if (!isset($_SESSION['IsAdmin']) || $_SESSION['IsAdmin'] != 1) {
            header("Location: ../login.php");
            exit();
        }

        if (!isset($order_id)) {
            echo "Error: No order ID provided.";
            exit;
        }

        $connection = $this->database->getConnection();

        $delete_order_query = "DELETE FROM orders WHERE OrderID = ?";
        $delete_order_stmt = $connection->prepare($delete_order_query);
        $delete_order_stmt->bind_param("i", $order_id);
        $delete_order_stmt->execute();

        if ($delete_order_stmt->affected_rows > 0) {
            echo "<script>alert('Order Deleted!');</script>";
            header("Location: ../checkout.php");
        } else {
            $response = array(
                "status" => "error",
                "message" => "Failed to delete order."
            );
            echo json_encode($response);
        }

        $delete_order_stmt->close();

        $connection->close();
    }
}

$adminOrderController = new AdminOrderController(new Database("localhost", "root", "", "coffee"));
$order_id = $_POST['order_id'] ?? null;
$adminOrderController->deleteOrder($order_id);
?>
