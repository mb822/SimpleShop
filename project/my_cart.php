
<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

$db = getDB();


//delete_all
if(isset($_POST["clear"])){
    $stmt = $db->prepare("DELETE FROM Cart where user_id = :uid");
    $r = $stmt->execute([ ":uid"=>get_user_id()]);
    if($r){
        flash("Bag has been cleared.");
    }
}




if(isset($_POST["delete"])){
    //$stmt = $db->prepare("DELETE FROM Cart  where id = :id");
    //$r = $stmt->execute([":id"=>$_POST["cartId"]]);
    //fix for example bug
    $stmt = $db->prepare("DELETE FROM Cart where id = :id AND user_id = :uid");
    $r = $stmt->execute([":id"=>$_POST["cartId"], ":uid"=>get_user_id()]);
    if($r){
        flash("Item removed from bag.");
    }
}

 $user = get_user_id();


if(isset($_POST["update"])){

	//flash($_POST["quantity"]);
	//flash($_POST["cartId"]);


	if($_POST["quantity"] == 0){
		$stmt = $db->prepare("DELETE FROM Cart where id = :id AND user_id = :uid");
   		 $r = $stmt->execute([":id"=>$_POST["cartId"], ":uid"=>get_user_id()]);
		
	}




	else{
    	    $stmt = $db->prepare("UPDATE Cart set Cart.quantity = :q where Cart.id = :id AND Cart.user_id = :uid");
    	    $r = $stmt->execute([":id"=>$_POST["cartId"], ":q"=>$_POST["quantity"] , ":uid"=>get_user_id()]);
	}


}




$results = [];

//    $user = get_user_id();
  //  $db = getDB();




    $stmt0 = $db->prepare("SELECT username FROM Users WHERE id = $user;");
    $r0 = $stmt0->execute();
    if ($r0) {
    $result = $stmt0->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
    flash("There was a problem fetching the results " . var_export($stmt->errorInfo(), true));
   }

    $username = $result[0]["username"];











$user = get_user_id();

$stmt = $db->prepare("SELECT  cart.id,  prod.name, cart.product_id ,  cart.quantity,  prod.price  , (cart.quantity*prod.price) as sub, prod.name FROM `Products` as prod JOIN `Cart` as cart ON prod.id = cart.product_id AND cart.user_id = $user;");
$stmt->execute([":id"=>get_user_id()]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tot = 0.0;
foreach($results as $r){
$tot += $r["sub"];
}


?>
    <div class="container-fluid">
        <label for= "pleasesignin">Your bag total is $<?php echo number_format(1.07*$tot, 2); ?></label>
        <div class="list-group">
        <?php if($results && count($results) > 0):?>
         <!--   <div class="list-group-item">
                <div class="row">
                    <div class="col">
                       Name
                    </div>
                    <div class="col">
                        Price
                    </div>
                    <div class="col">
                        Quantity
                    </div>
                    <div class="col">
                        Subtotal
                    </div>
                    <div class="col">
                        Actions
                    </div>
                </div>
            </div>       -->
            <?php foreach($results as $r):?>
            <div class="list-group-item">
                <form method="POST">
                <div class="row">
                    <div class="col">
                       


		<a type="button" class="prod_name"  href="test_view_products.php?id=<?php safer_echo($r['product_id']); ?>"><?php echo $r["name"];?></a>

                    </div>
                <!--    <div class="col">
                        <?php echo "$".$r["price"];?>
                    </div>    -->
                    <div class="col">

                            <input type="number" min="0" name="quantity" value="<?php echo $r["quantity"];?>"/>
                            <input type="hidden" name="cartId" value="<?php echo $r["id"];?>"/>

                    </div>
                    <div class="col">
                        <?php echo "$".$r["sub"];?>
                    </div>
                    <div class="col">
                        <!-- form split was on purpose-->
                        
                <!--        </form>
                        <form method="POST">      -->
                            <input type="hidden" name="cartId" value="<?php echo $r["id"];?>"/>
			    <input type="submit" class="btn btn-success" name="update" value="Update"/>
                            <input type="submit" class="btn btn-danger" name="delete" value="Remove Item"/>
                        </form>
                    </div>
                </div>
            </div>

            <?php endforeach;?>


	<form method="POST">
		<input type="submit" class="btn btn-danger" name="clear" value="Clear Bag"/>
	</form>

	<?php echo "Subtotal: "."$".number_format($tot,2);?>
        <?php echo "Tax: ". "$".number_format($tot*0.07,2);?>
	<?php echo "Total: "."$".(number_format($tot*1.07,2));?>

        <?php else:?>
        <div class="list-group-item">
            No items in cart
        </div>
        <?php endif;?>
        </div>
    </div>
<?php require(__DIR__ . "/partials/flash.php")?>
