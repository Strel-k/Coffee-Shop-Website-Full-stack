<?php
require_once "script/database.php"; 
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
if (isset($_SESSION['Accountid'])) {
    $customerID = $_SESSION['Accountid'];
    $query = "SELECT IsAdmin FROM customeraccounts WHERE CustomerID = $customerID";
    $result = mysqli_query($connection, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $isAdmin = isset($row['IsAdmin']) && $row['IsAdmin'] == 1 ? true : false;
    }
} else {
    $isAdmin = false;
}
$database = new Database("localhost", "root", "", "coffee");

$connection = $database->getConnection();

$accountID = $_SESSION['Accountid'];

$query = "SELECT ca.*, c.Name AS CustomerName, c.Email, c.Phone FROM customeraccounts ca JOIN customers c ON ca.CustomerID = c.CustomerID";
$result = mysqli_query($connection, $query);

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
    <title>Manage Accounts</title>
</head>
<body style="background-color:black;">
    <?php include "script/header.php";?>
    <div class="container" style="background-color: white;">
        <div class="accounts">
            <h2 style="text-align:center;">Manage Accounts</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) { 
                            if ($row['CustomerID'] == $accountID) {
                                continue; 
                            }
                    ?>
                            <tr>
                                <td><?= $row['CustomerName'] ?></td>
                                <td><?= $row['Email'] ?></td>
                                <td><?= $row['Phone'] ?></td>
                                <td>
                                    <button class="edit-btn" onclick="location.href='edit-form.php?customerid=<?= $row['CustomerID'] ?>&customername=<?= urlencode($row['CustomerName']) ?>'" >Edit</button>
                                    <button class="delete-btn" onclick="deleteCustomer(<?= $row['CustomerID'] ?>)">Delete</button>
                                </td>
                            </tr>
                    <?php }} else { ?>
                        <tr><td colspan='5'>No accounts found</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include "script/footer.php";?>
    <?php mysqli_close($connection); ?>

    <script>
        function deleteCustomer(customerId) {
            if (confirm("Are you sure you want to delete this customer?")) {
                window.location.href = "script/delete-account.php?customer_id=" + customerId;
            }
        }
    </script>
     <?php if($isAdmin) {
         include "adminpanel.php"; 
    }
    ?>
</body>
</html>
