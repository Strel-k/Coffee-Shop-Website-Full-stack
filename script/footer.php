<?php

class Footer {
    public function generateFooter() {
        echo '<div class="container" style="background-color: black; margin-top:25px;">';
        echo '<br>';
        echo '<hr style="color:white;">';
        echo '<span style="color:white; display: flex; text-align: center;"> © 2023 Apayawa Coffee Company. All rights reserved.</span>';
        echo '<br> <br>';
        echo '<footer>';
        echo '<ul class="social-links">';
        echo '<li> <a href="https://www.facebook.com/fdave.pararuan"> <img src="img/Facebook.png" alt="fb link"></a></li>';
        echo '<li> <a href="https://www.youtube.com/channel/UChA8tlejLbJ15c1Gq4G7RHQ"> <img src="img/Email.png"> </a></li>';
        echo '<li> <a href="#"> <img src="img/Phone_icon.png"></a></li>';
        echo '</ul>';
        echo '</footer>';
        echo '</div>';
    }
}

$footer = new Footer();
$footer->generateFooter();

?>
