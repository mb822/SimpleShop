<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
    <label for= "pleasesignin">Create Cart.</label>
    <form method="POST">
        
        <input name="product_id" placeholder="Product ID"/>
        <input type="number" min="1" name="quantity" placeholder="Quantity"/>

        <input type="submit" name="save" value="Create"/>
    </form>

<?php
if (isset($_POST["save"])) {
    //TODO add proper validation/checks
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    $user = get_user_id();
    $db = getDB();


    
    

    $priceAry = (($db->query("SELECT price FROM Products WHERE id = $product_id"))->fetchAll());
//    if(empty($result)){flash("Product does not exist.");}
//    $result = $result[0];
//    if(empty($result)){flash("Product does not exist.");}
//    $price = $result[0];
   // flash($price);
if(!empty($priceAry)){
        $price = $priceAry[0][0];



  //foreach($price as $x => ){flash($x);}

 //    for($x = 0; $x < count($price); $x++){flash($x."<index, value>".$price[$x]);}

 // if(is_null($price)){flash("No such product found");return;}

    $stmt = $db->prepare("INSERT INTO Cart (product_id, quantity, user_id, price) VALUES (:product_id, :quantity, :user, :price)");
    $r = $stmt->execute([
        ":product_id" => $product_id,
        ":quantity" => $quantity,
        ":user" => $user,
        ":price" => $price[0]
    ]);
    if ($r) {
        flash("Created successfully with id: " . $db->lastInsertId());
    }
    else {
        $e = $stmt->errorInfo();
        flash("Error creating: " . var_export($e, true));
    }
}
else{flash("Invalid Product ID");}
}
?>
<?php require(__DIR__ . "/partials/flash.php"); 
