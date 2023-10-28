<?php require_once(__DIR__ . "/partials/nav.php"); ?>
    <form method="POST">
    <label for= "pleasesignin" style="font-size: 3.0em;" >Your order has been placed.</label>
    <h2 style="    margin-left: 11%;color: #006ac7;" >Thanks for choosing !Apple.</h2>
    <h4 style="margin-top:50px; margin-left:11%" >  <?php echo "Order #: ".$_SESSION["order_number"];   ?>    </h4>
    <!-- <input type="submit" name="login" value="Login"/> -->
    </form>   

<?php
$_SESSION["order_number"] = "";
$_SESSION["currentcartitems"] = [];
?>


<?php require_once(__DIR__ . "/partials/flash.php");
