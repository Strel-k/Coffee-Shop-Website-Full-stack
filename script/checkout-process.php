<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$dbname = "coffee";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['product_id']) && isset($_POST['quantity'])) {
    if(!isset($_SESSION['Accountid'])) {
        echo "Error: User not logged in. Please log in before making a purchase.";
        exit;
    }

    $customerID = $_SESSION['Accountid'];
    $productID = $conn->real_escape_string($_POST['product_id']);
    $quantity = $conn->real_escape_string($_POST['quantity']);

    $orderDate = date('Y-m-d H:i:s');
    
    echo "Customer ID: " . $customerID . "<br>";
    echo "Product ID: " . $productID . "<br>";
    echo "Quantity: " . $quantity . "<br>";
    echo "Order Date: " . $orderDate . "<br>";

    $insertOrderQuery = "INSERT INTO orders (CustomerID, OrderDate) VALUES ('$customerID', '$orderDate')";
    $result = $conn->query($insertOrderQuery);
    
    if(!$result) {
        echo "Error: Unable to create order. " . $conn->error;
        exit;
    }

    $orderID = $conn->insert_id;
    
    echo "Inserted Order ID: " . $orderID . "<br>";

    $insertOrderItemQuery = "INSERT INTO orderitems (OrderID, ProductID, Quantity) VALUES ('$orderID', '$productID', '$quantity')";
    $result = $conn->query($insertOrderItemQuery);

    if(!$result) {
        echo "Error: Unable to add product to the order. " . $conn->error;
        exit;
    }

    echo "success";
} else {
    echo "Error: Form not submitted.";
}

$conn->close();
?>
