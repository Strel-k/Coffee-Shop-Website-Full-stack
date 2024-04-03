<?php

class Header {
    private $sessionStatus;

    public function __construct() {
        $this->sessionStatus = session_status();
    }
    
    public function generateHeader() {
        echo '<a href="../index.php"><img src="img/Coffee-Logo.png" class="coffee-logo" alt="Coffee Logo"></a>';
        echo '<div class="container" style="background-color: white; border-radius:25px; margin-top:15px;">';
        echo '<div class="header-content">';
        echo '<a href="index.php"> <h3>Home</h3></a>';
        echo '<a href="store.php"> <h3>Store</h3></a>';
        echo '<a href="checkout.php"><h3>Checkout</h3></a>';
        
        if (isset($_SESSION['Accountid'])) {
            echo '<a href="script/logout.php"><h3>Sign Out</h3></a>';
        } else {
            echo "<a href='../login.php'><h3>Login</h3></a>";
            echo "<a href='../register.php'><h3>Register</h3></a>";
        }
        
        echo '</div>';
        echo '</div><br>';
    }
}    

$header = new Header();
$header->generateHeader();

?>