<?php
include "database.php"; 
class OrderController {
    private $database;
    private $isAdmin;
    private $customerID;

    public function __construct(Database $database, $isAdmin, $customerID) {
        $this->database = $database;
        $this->isAdmin = $isAdmin;
        $this->customerID = $customerID;
    }

    public function getOrders() {
        $connection = $this->database->getConnection(); 

        if ($this->isAdmin) {
            $query = "SELECT c.Name AS CustomerName, c.Email, c.Phone, o.OrderID, o.OrderDate, p.Name AS ProductName, p.Price, oi.Quantity
                      FROM orders o
                      INNER JOIN customers c ON o.CustomerID = c.CustomerID
                      LEFT JOIN orderitems oi ON o.OrderID = oi.OrderID
                      LEFT JOIN products p ON oi.ProductID = p.ProductID";
            $stmt = $connection->prepare($query);
        } else {
            $query = "SELECT c.Name AS CustomerName, c.Email, c.Phone, o.OrderID, o.OrderDate, p.Name AS ProductName, p.Price, oi.Quantity
                      FROM orders o
                      INNER JOIN customers c ON o.CustomerID = c.CustomerID
                      LEFT JOIN orderitems oi ON o.OrderID = oi.OrderID
                      LEFT JOIN products p ON oi.ProductID = p.ProductID
                      WHERE o.CustomerID = ?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("i", $this->customerID);
        }

        $stmt->execute();
        
        $result = $stmt->get_result();

        $stmt->close();

        $connection->close();

        return $result;
    }
}

$database = new Database("localhost", "root", "", "coffee");
$isAdmin = isset($_SESSION['IsAdmin']) ? $_SESSION['IsAdmin'] : false;
$customerID = isset($_SESSION['Accountid']) ? $_SESSION['Accountid'] : null;

$orderController = new OrderController($database, $isAdmin, $customerID);
$result = $orderController->getOrders();

?>
