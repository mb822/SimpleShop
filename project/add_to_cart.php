<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php

if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("Please login to add to bag.");
    die(header("Location: login.php"));
}
?>
<!--    <label for= "pleasesignin">Create Cart.</label>
    <form method="POST">

        <input type= "hidden" name="product_id" placeholder="Product ID"/>
        <input type="number" min="1" name="quantity" placeholder="Quantity"/>

        <input type="submit" name="save" value="Create"/> 
    </form>
-->
<?php
//////////////////if (isset($_POST["save"])) {
    //TODO add proper validation/checks
   // $product_id = $_POST["product_id"];
  //  $quantity = $_POST["quantity"];
    $user = get_user_id();
    $db = getDB();

if(isset($_GET["id"])){
        $id = $_GET["id"];
}



    $priceAry = (($db->query("SELECT price FROM Products WHERE id = $id"))->fetchAll());

if(!empty($priceAry)){
        $price = $priceAry[0][0];



    $stmt = $db->prepare("INSERT INTO Cart (product_id, quantity, user_id, price) VALUES (:product_id, :quantity, :user, :price)");
    $r = $stmt->execute([
        ":product_id" => $id,
        ":quantity" => 1,
        ":user" => $user,
        ":price" => $price[0]
    ]);
    if ($r) {
	//flash("Product added to cart");
        die(header("Location: my_cart.php"));
    }
    else {
	$e = $stmt->errorInfo();
        flash("Product already in bag.");
    }
}
else{flash("Error.");}

/////////////////////////}
?>
<?php require(__DIR__ . "/partials/flash.php");


