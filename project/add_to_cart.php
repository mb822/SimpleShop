<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//ADD PRODUCT TO CART
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("Please login to add to bag.");
    die(header("Location: login.php"));
}
?>
<?php
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
	die(header("Location: my_cart.php"));
    }
}
else{flash("Error.");}
?>
<?php require(__DIR__ . "/partials/flash.php");
?>
