<?php
include "database.php"; 

class ProductController {
    private $database;

    public function __construct(Database $database) {
        $this->database = $database;
    }

    public function getProductDetails($productId) {
        $connection = $this->database->getConnection();

        $productId = mysqli_real_escape_string($connection, $productId);

        $query = "SELECT * FROM products WHERE ProductID = $productId";

        $result = mysqli_query($connection, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $productDetails = mysqli_fetch_assoc($result);

            mysqli_close($connection);

            header('Content-Type: application/json');

            echo json_encode($productDetails);
        } else {
            echo json_encode(array('error' => 'Product not found'));
        }

        mysqli_close($connection);
    }
}

$productController = new ProductController(new Database("localhost", "root", "", "coffee"));
if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    $productController->getProductDetails($productId);
} else {
    echo json_encode(array('error' => 'Product ID not provided'));
}
?>
