<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "coffee";

$connection = mysqli_connect($servername, $username, $password, $dbname);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['Accountid'])) {
    header("Location: login.php");
    exit; 
}

$accountID = $_SESSION['Accountid'];

$isAdmin = getIsAdminStatus($connection, $accountID);

if (isset($_POST['edit_quantity'])) {
    $order_id = $_POST['order_id'];
    $new_quantity = $_POST['new_quantity'];

    updateQuantity($connection, $order_id, $new_quantity);
}

mysqli_close($connection);

function getIsAdminStatus($connection, $accountID) {
    $query = "SELECT IsAdmin FROM customers WHERE CustomerID = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $accountID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $isAdmin);
    mysqli_stmt_fetch($stmt);
    $_SESSION['IsAdmin'] = $isAdmin;
    mysqli_stmt_close($stmt);
    return $isAdmin;
}

function updateQuantity($connection, $order_id, $new_quantity) {
    $check_query = "SELECT * FROM orderitems WHERE OrderID = ?";
    $check_stmt = mysqli_prepare($connection, $check_query);
    mysqli_stmt_bind_param($check_stmt, "i", $order_id);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        $update_query = "UPDATE orderitems SET Quantity = ? WHERE OrderID = ?";
        $update_stmt = mysqli_prepare($connection, $update_query);
        mysqli_stmt_bind_param($update_stmt, "ii", $new_quantity, $order_id);
        mysqli_stmt_execute($update_stmt);

        if (mysqli_stmt_affected_rows($update_stmt) > 0) {
            echo "Quantity updated successfully.";
        } else {
            echo "Failed to update quantity.";
        }

        mysqli_stmt_close($update_stmt);
    } else {
        echo "Order ID not found.";
    }

    mysqli_stmt_close($check_stmt);
}
?>



<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/checkout.css">
    <link rel="stylesheet" type="text/css" href="css/font.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>

    <title>Checkout</title>
</head>
<body style="background-color:black;">
<?php include "script/header.php";?>
<div class="container" style="background-color: white;">
    <div class="product">
        <h2 style="text-align:center;">Checkout Items</h2>
        <div class="table-responsive">
        <table class="table">
    <thead>
        <tr>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Order Date</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>
    <?php
    
    include "script/fetch-checkout.php";
    
    if ($result && mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['CustomerName'] . "</td>";
            echo "<td>" . $row['Email'] . "</td>";
            echo "<td>" . $row['Phone'] . "</td>";
            echo "<td>" . $row['OrderDate'] . "</td>";
            echo "<td>" . $row['ProductName'] . "</td>";
            echo "<td>" . $row['Price'] . "</td>";
            echo "<td>" . $row['Quantity'] . "</td>";
            if ($isAdmin) {
                echo "<td>";
            echo "<form method='post' action='script/edit-quantity.php'>";
            echo "<input type='hidden' name='order_id' value='" . $row['OrderID'] . "'>";
            echo "<input type='number' name='new_quantity' value='" . $row['Quantity'] . "' min='1'>";
            echo "<input type='submit' name='edit_quantity' value='Edit Quantity'>";
            echo "</form>";
            echo "<form method='post' action='script/delete-order.php' onsubmit='return confirm(\"Are you sure you want to delete this order?\")'>";
            echo "<input type='hidden' name='order_id' value='" . $row['OrderID'] . "'>";
            echo "<input type='submit' name='delete_order' value='Delete Order'>";
            echo "</form>";
            echo "</td>";
            }
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8'>No items found</td></tr>";
    }
    ?>
    </tbody>
</table>
</div>
    </div>
</div>
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="successMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php include "script/footer.php";?>
<script>
    $(".editForm").submit(function(event) {
        event.preventDefault(); 
        var form = $(this);
        $.ajax({
            type: "POST",
            url: "script/edit-quantity.php",
            data: form.serialize(), 
            success: function(response) {
                if (response.status === "success") {
                    alert(response.message);
                    location.reload();
                } else {
                    alert("Failed to update quantity: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred. Please try again later.");
            }
        });
    });
</script>
<?php if($isAdmin) {
         include "adminpanel.php"; 
    }
    ?>


</body>
</html>