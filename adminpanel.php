<script src="js/jquery-3.7.1.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<style>
    .admin-panel-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 10px;
        background-color: white;
        color: white;
        border: none;
        border-radius: 55px;
        cursor: pointer;
        width: 4%;
    }

    .admin-panel-options {
        position: fixed;
        bottom: 12%;
        right: 100;
        left:95%;
        display:none;
        padding-right:55px;
    }

    .admin-panel-options button {
        display: inline-block;
        margin-top: 5px;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        border-radius: 15px;
        cursor: pointer;
        width: auto;
    }

    .admin-panel-options button img {
        width: 30px;
        vertical-align: middle; 
    }

    #adminPanelOptions button:nth-child(1) {
        background-color: white; 
    }
    
    #adminPanelOptions button:nth-child(2) {
        background-color: white; 
    }
    
    #adminPanelOptions button:nth-child(3) {
        background-color: white;
    }
</style>


<button id="adminPanelBtn" class="admin-panel-btn"><img src="img/tool.png"></button>

<div id="adminPanelOptions" class="admin-panel-options">
    <button onclick="location.href='accounts.php';"><img src="img/Accounts.png"></button>
    <button onclick="location.href='store.php';"><img src="img/product.png"></button>
    <button onclick="location.href='checkout.php';"><img src="img/checkout.png"></button>
</div>

<script>
    $(document).ready(function() {
        $('#adminPanelOptions').hide();

        $('#adminPanelBtn').click(function() {
            $('#adminPanelOptions').toggle();
        });
    });
</script>
