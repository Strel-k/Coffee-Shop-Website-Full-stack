<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/store.css">
    <link rel="stylesheet" type="text/css" href="css/font.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <script src="js/jquery-3.7.1.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/modality.js"></script>
    <title>Store</title>
</head>
<body style="background-color: black; margin-top:15;">

<?php 
    include "script/database.php";
    include "script/header.php";
   
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "coffee";
    $database = new Database($servername, $username, $password, $dbname);
    $database->connect();

    $orderSubmitted = false; 

    if(isset($_SESSION['Accountid'])) {
        $customerID = $_SESSION['Accountid'];
        $query = "SELECT IsAdmin FROM customeraccounts WHERE CustomerID = $customerID";
        $result = mysqli_query($database->getConnection(), $query);
        if($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $isAdmin = isset($row['IsAdmin']) && $row['IsAdmin'] == 1 ? true : false;
        }
    }
    $isAdmin = false;
    if(isset($_SESSION['Accountid'])) {
        $customerID = $_SESSION['Accountid'];
        $query = "SELECT IsAdmin FROM customeraccounts WHERE CustomerID = $customerID";
        $result = mysqli_query($database->getConnection(), $query);
        if($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $isAdmin = isset($row['IsAdmin']) && $row['IsAdmin'] == 1 ? true : false;
        }
    }

    function handleProductAction($database) {
        if(isset($_POST['add_product'])) {
            $productName = $_POST['product_name'];
            $productPrice = $_POST['product_price'];
            
            $uploadDirectory = 'uploads/';
            $uploadFile = $uploadDirectory . basename($_FILES['product_image']['name']);
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadFile)) {
                $productImageURL = $uploadFile;
        
                // Insert the product into the database
                $insertProductQuery = "INSERT INTO products (Name, Price, image_url) VALUES ('$productName', '$productPrice', '$productImageURL')";
                
                // Execute the query
                $result = mysqli_query($database->getConnection(), $insertProductQuery);
        
                if($result) {
                    $GLOBALS['orderSubmitted'] = true;
                    header("Location: store.php");
                    exit();
                } else {
                    echo "Error: " . mysqli_error($database->getConnection());
                }
            } else {
                echo "Error uploading file.";
            }
        } elseif(isset($_POST['edit_product'])) {
            $productId = isset($_POST['product_id']) ? $_POST['product_id'] : '';
            $productName = $_POST['product_name'];
            $productPrice = $_POST['product_price'];
        
            // Handle image upload
            $uploadDirectory = 'uploads/';
            $uploadFile = $uploadDirectory . basename($_FILES['product_image']['name']);
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadFile)) {
                $productImageURL = $uploadFile;
            } else {
                // If no new image is uploaded, keep the existing image URL
                $productImageURL = $_POST['product_image_url'];
            }
        
            // Update the product in the database
            $updateProductQuery = "UPDATE products SET Name='$productName', Price='$productPrice', image_url='$productImageURL' WHERE ProductID=$productId";
            
            // Execute the query
            $result = mysqli_query($database->getConnection(), $updateProductQuery);
        
            if(!$result) {
                echo "Error: " . mysqli_error($database->getConnection());
            }
            else {
                header("Location: store.php");
                exit();
            }
        } elseif(isset($_POST['delete_product'])) {
            $productId = $_POST['product_id']; 
        
            $deleteOrderItemsQuery = "DELETE FROM orderitems WHERE ProductID = $productId";
            $result = mysqli_query($database->getConnection(), $deleteOrderItemsQuery);
        
            if($result) {
                $deleteProductQuery = "DELETE FROM products WHERE ProductID = $productId";
        
                $result = mysqli_query($database->getConnection(), $deleteProductQuery);
        
                if(!$result) {
                    echo "Error: " . mysqli_error($database->getConnection());
                }
                else {
                    header("Location: store.php");
                    exit();
                }
            } else {
                echo "Error: " . mysqli_error($database->getConnection());
            }
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buy_product'])) {
        // Handling purchase action
        $productId = $_POST['product_id'];
        $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
        $userId = isset($_SESSION['Accountid']) ? $_SESSION['Accountid'] : null;

        // Check if the user is logged in
        if ($userId) {
            // Check if the user ID exists in the customeraccounts table
            $checkUserQuery = "SELECT * FROM customeraccounts WHERE AccountID = $userId";
            $resultUser = mysqli_query($database->getConnection(), $checkUserQuery);
            if ($resultUser && mysqli_num_rows($resultUser) > 0) {
                // Fetch the user's CustomerID from the customeraccounts table
                $row = mysqli_fetch_assoc($resultUser);
                $customerId = $row['CustomerID'];

                // Insert the order into the orders table
                $insertOrderQuery = "INSERT INTO orders (CustomerID, user_id) VALUES ('$customerId', '$userId')";
                $result = mysqli_query($database->getConnection(), $insertOrderQuery);

                if ($result) {
                    // Get the OrderID of the newly inserted order
                    $orderId = mysqli_insert_id($database->getConnection());

                    // Insert the order item into the orderitems table
                    $insertOrderItemQuery = "INSERT INTO orderitems (OrderID, ProductID, Quantity) VALUES ('$orderId', '$productId', '$quantity')";
                    $resultOrderItem = mysqli_query($database->getConnection(), $insertOrderItemQuery);

                    if ($resultOrderItem) {
                        echo "<script>$('#successModal').modal('show');</script>";
                    } else {
                        echo "Error adding product to cart: " . mysqli_error($database->getConnection());
                    }
                } else {
                    // Error handling for order insertion
                    echo "Error creating order: " . mysqli_error($database->getConnection());
                }
            } else {
                // Error: User ID does not exist in the customeraccounts table
                echo "Error: User ID does not exist";
            }
        } else {
            // Error: User is not logged in
            echo "Error: User is not logged in";
        }
    }

    handleProductAction($database);

    $limit = 12;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $start = ($page - 1) * $limit;
    
    $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
    $searchCondition = !empty($searchQuery) ? "WHERE Name LIKE '%$searchQuery%'" : '';
    
    $totalProductsQuery = "SELECT COUNT(*) AS total FROM products $searchCondition";
    $totalProductsResult = mysqli_query($database->getConnection(), $totalProductsQuery);
    $totalProductsRow = mysqli_fetch_assoc($totalProductsResult);
    $totalProducts = $totalProductsRow['total'];
    
    $totalPages = ceil($totalProducts / $limit);
    
    $query = "SELECT * FROM products $searchCondition LIMIT $start, $limit";
    $result = mysqli_query($database->getConnection(), $query);
    
    $productCount = 0;
?>

<div class="container" style="background-color:white; padding-bottom:5px;">
    <br>
  <?php
        echo '<form action="" method="GET" class="search-form">';
        echo '<input type="text" name="search" placeholder="Search products" class="search-input">';
        echo '<button type="submit"class="search-button" >Search</button>';
        echo '</form>';
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $productCount++;
            echo "<div class='product-container'>";
            echo "<input type='hidden' name='product_id' value='" . $row['ProductID'] . "'>";
            echo "<input type='hidden' name='product_name' value='" . $row['Name'] . "'>";
            echo "<input type='hidden' name='product_price' value='" . $row['Price'] . "'>";
            echo "<img src='" . $row['image_url'] . "' alt='Product Image'>";
            echo "<div class='product-details'>";
            echo "<h4>" . $row['Name'] . "</h4>";
            echo "<p>Price: P" . $row['Price'] . "</p>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='product_id' value='" . $row['ProductID'] . "'>";
            echo "<input type='hidden' name='product_name' value='" . $row['Name'] . "'>";
            echo "<input type='hidden' name='product_price' value='" . $row['Price'] . "'>";
            echo "<input type='hidden' name='product_image_url' value='" . $row['image_url'] . "'>";
            if($isAdmin){
                echo "<button id='showEditProductModalBtn" . $row['ProductID'] . "' class='edit-btn'>Edit</button>";

                echo "<button type='submit' name='delete_product' onclick='return confirmDelete()' style='padding:5px; background-color:red; width:25%;'>Delete</button>";
                
            }
            echo "<button type='submit' name='buy_product' class='buy-btn' data-product-id='" . $row['ProductID'] . "' style='padding:5px; background-color:green;  width:25%;'>Buy</button>
            ";
            echo "</form>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "Error: Unable to fetch products";
    }

    if ($productCount == 0) {
        echo "<p>No products available</p>";
    }
    ?>

    <?php if($isAdmin): ?>
        <button id="showAddProductModalBtn" class="add-btn">Add A New Product</button>
    <?php endif; ?>
</div>
<div class="pagination">
<?php for($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?page=<?php echo $i; ?>&search=<?php echo $searchQuery; ?>"><?php echo $i; ?></a>
<?php endfor; ?>
</div>
<div id="buyModal" class="modal fade" role="dialog" aria-labelledby="buyModalLabel">
    <div class="modal-dialog" style="max-width: 50%; margin-top: 10%;">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="buyModalLabel">Select Quantity</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button> 
        </div>
        <div class="modal-body" id="buyModalBody">
        </div>
    </div>
    </div>
</div>
<div id="editProductModal" class="modal fade">
<div class="modal-dialog" style="max-width: 50%; margin-top: 10%;">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Product</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="editProductForm" method="post" style="text-align: center;" enctype="multipart/form-data">
                <input type="hidden" id="editProductId" name="product_id">
                <label for="editProductName" style="display: block; margin-bottom: 10px;">Product Name:</label>
                <input type="text" id="editProductName" name="product_name" required style="width: 80%; padding: 10px; margin-bottom: 10px;"><br>
                <label for="editProductPrice" style="display: block; margin-bottom: 10px;">Product Price:</label>
                <input type="text" id="editProductPrice" name="product_price" required style="width: 80%; padding: 10px; margin-bottom: 10px;"><br>
                <label for="editProductImage" style="display: block; margin-bottom: 10px;">Product Image:</label>
                <input type="file" id="editProductImage" name="product_image" accept="image/*" style="margin-bottom: 10px;"><br>
                <button type="submit" name="edit_product" style="padding: 10px 20px; background-color: gray; color: white; border: none; border-radius: 5px; cursor: pointer;">Update Product</button>
            </form>
        </div>
    </div>
</div>
</div>

<div id="addProductModal" class="modal fade">
    <div class="modal-dialog" style="max-width: 50%; margin-top: 10%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Product</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button> 
            </div>
            <div class="modal-body">
    <form id="addProductForm" method="post" style="text-align: center;" enctype="multipart/form-data">
    <input type="hidden" id="addProductId" name="product_id">
    <label for="addProductName" style="display: block; margin-bottom: 10px;">Product Name:</label>
    <input type="text" id="addProductName" name="product_name" required style="width: 80%; padding: 10px; margin-bottom: 10px;"><br>
    <label for="addProductPrice" style="display: block; margin-bottom: 10px;">Product Price:</label>
    <input type="text" id="addProductPrice" name="product_price" required style="width: 80%; padding: 10px; margin-bottom: 10px;"><br>
    <label for="addProductImage" style="display: block; margin-bottom: 10px;">Product Image:</label>
    <input type="file" id="addProductImage" name="product_image" accept="image/*" style="margin-bottom: 10px;"><br>
    <button type="submit" name="add_product" style="padding: 10px 20px; background-color: gray; color: white; border: none; border-radius: 5px; cursor: pointer;">Add Product</button>
</form>
</div>
</div>
</div>
</div>
<div id="successModal" class="modal fade">
    <div class="modal-dialog" style="max-width: 50%; margin-top: 10%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Success!</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="successMessage">
                <h3>Product has been placed to your Cart!</h3>
            </div>
        </div>
    </div>
</div>
<script src="js/jquery-3.7.1.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/modality.js"></script>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this product?");
    }
</script>

<script>
    $('.buy-btn').click(function() {
        var productId = $(this).data('product-id');
        $('#buyModalBody').html(
            '<form method="post">' +
            '<input type="hidden" name="product_id" value="' + productId + '">' +
            '<input type="number" name="quantity" placeholder="Enter quantity" required>' +
            '<button type="submit" name="buy_product" style="padding: 5px; background-color: green; color: white; border: none; border-radius: 5px; cursor: pointer;">Buy</button>' +
            '</form>'
        );
        $('#buyModal').modal('show');
    });

    $('.edit-btn').click(function(event) {
    event.preventDefault(); 
    var productId = $(this).attr('id').replace('showEditProductModalBtn', '');
    var productName = $(this).siblings('input[name="product_name"]').val();
    var productPrice = $(this).siblings('input[name="product_price"]').val();
    var productImageUrl = $(this).siblings('input[name="product_image_url"]').val();
    
    $('#editProductId').val(productId);
    $('#editProductName').val(productName);
    $('#editProductPrice').val(productPrice);
    
    $('#editProductModal').modal('show');
});

    $('#showAddProductModalBtn').click(function() {
        $('#addProductModal').modal('show');
    });
</script>
<script>
    function showThankYouModal() {
        $('#successModal').modal('show');
    }
</script>
<?php include "script/footer.php";?>
<?php if($isAdmin) {
         include "adminpanel.php"; 
    }
    ?>
</body>
</html>

