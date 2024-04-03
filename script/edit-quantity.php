<?php
session_start();
require_once "database.php"; // Assuming database.php contains the Database class definition

class AdminOrderController {
    private $database;

    public function __construct(Database $database) {
        $this->database = $database;
    }

    public function updateOrderQuantity($order_id, $new_quantity) {
        if (!isset($_SESSION['IsAdmin']) || $_SESSION['IsAdmin'] != 1) {
            http_response_code(403);
            $response = array(
                "status" => "error",
                "message" => "You are not authorized to perform this action."
            );
            echo json_encode($response);
            exit;
        }

        if (!isset($new_quantity)) {
            http_response_code(400);
            $response = array(
                "status" => "error",
                "message" => "Edit quantity data not received."
            );
            echo json_encode($response);
            exit;
        }

        $connection = $this->database->getConnection();

        $query = "UPDATE orderitems SET Quantity = ? WHERE OrderID = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ii", $new_quantity, $order_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Record Updated!');</script>";

            echo "<script>";
            echo "$('#successModal').modal('show');";
            echo "</script>";
            header("Location: ../checkout.php");
            exit();
        } else {
            $response = array(
                "status" => "error",
                "message" => "Failed to update quantity."
            );
            echo json_encode($response);
        }

        $stmt->close();
        $connection->close();
    }
}

$adminOrderController = new AdminOrderController(new Database("localhost", "root", "", "coffee"));
$order_id = $_POST['order_id'] ?? null;
$new_quantity = $_POST['new_quantity'] ?? null;
$adminOrderController->updateOrderQuantity($order_id, $new_quantity);
?>
