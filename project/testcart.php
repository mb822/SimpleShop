
<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

$db = getDB();

//REMOVE ALL ITEMS
//delete_all
if(isset($_POST["clear"])){
    $stmt = $db->prepare("DELETE FROM Cart where user_id = :uid");
    $r = $stmt->execute([ ":uid"=>get_user_id()]);
    if($r){
        flash("Bag has been cleared.");
    }
}



//REMOVE SINGLE ITEM
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


	if($_POST["prod_quantity"] == 0){

		if($_POST["prod_quantity"] == 0){flash("Sorry. ".$_POST["name"]." is out of stock and has been removed."  );}

		$stmt = $db->prepare("DELETE FROM Cart where id = :id AND user_id = :uid");
   		 $r = $stmt->execute([":id"=>$_POST["cartId"], ":uid"=>get_user_id()]);
		
	}


	

	else{
	    $updatedQuanity = $_POST["quantity"];
	    if($_POST["quantity"] > $_POST["prod_quantity"] ){
		$updatedQuanity = $_POST["prod_quantity"];
	    }

    	    $stmt = $db->prepare("UPDATE Cart set Cart.quantity = :q where Cart.id = :id AND Cart.user_id = :uid");
    	    $r = $stmt->execute([":id"=>$_POST["cartId"], ":q"=>$updatedQuanity, ":uid"=>get_user_id()]);
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

$stmt = $db->prepare("SELECT  cart.id,  prod.name, cart.product_id ,  cart.quantity,  prod.price, prod.checkout_img, prod.quantity as prod_quantity,
(CASE
    WHEN cart.quantity <= prod.quantity THEN cart.quantity * prod.price
    ELSE prod.quantity * prod.price
END) as sub, 
prod.name FROM `Products` as prod JOIN `Cart` as cart ON prod.id = cart.product_id AND cart.user_id = :id");


$stmt->execute([":id"=>get_user_id()]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tot = 0.0;
foreach($results as $r){
$tot += $r["sub"];
}


?>
    <div class="container-fluid">
        <label for= "pleasesignin" style="margin-left:20%; font-size:2.5em" >Your bag total is $<?php echo number_format(1.07*$tot, 2); ?></label>

        <?php if($results && count($results) > 0):?>
    














    






            <?php foreach($results as $r):?>
            <div class="cart_item_div">

    <img aria-hidden="true"  src="<?php echo $r["checkout_img"]?>" width="200" height="240" alt="" class="ir">




        <div class="rs-iteminfo-details" style="display:flex;">
	<div class="large-6 rs-iteminfo-title-wrapper small-12 "  style="margin-left:40px;min-width:350px; width:600px;"  >
            
            <a class="item_name"  style="min-width: 350px; font-size: 1.75em;font-weight: 500;"  href="test_view_products.php?id=<?php safer_echo($r['product_id']); ?>"><?php echo $r["name"];?>
            </a>
            </h2>
        </div>
<!--    <select aria-label="Quantity" value="1" class="rs-quantity-dropdown form-dropdown-select" id="shoppingCart.items.item-75e17daa-1111-448e-8f0c-541e2fc32311.itemQuantity" style="width: 2.35294rem;">
        <option value="1" selected="true">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
    </select> -->



	<form method="POST" style="margin: 0px 11%; display:flex" >
		<input type="hidden" name="cartId" value="<?php echo $r["id"];?>"/>

		
		<?php if ($r["quantity"]  >=  $r["prod_quantity"]  ): ?>
			<?php  flash("Stock of ".$r["name"] ." is low. It's quantity has been updated.");   ?>
			<input type="number" min="0" name="quantity" value="<?php echo $r["prod_quantity"];?>"  style="border-color: #ffd700; margin: 10px 11%;padding: 6px 12px; width:65px;border-radius: 20px;height: 40px; "  >
		<?php endif; ?>
		
		<?php if ($r["quantity"]  <  $r["prod_quantity"]  ): ?>
                        <input type="number" min="0" name="quantity" value="<?php echo $r["quantity"];?>"  style=" margin: 10px 11%;padding: 6px 12px; width:65px;border-radius: 20px;height: 40px; "  >
                <?php endif; ?>






	

	<!-- </form> -->



<div class="large-last rs-iteminfo-pricedetails" style="width:200px">
<div>
<p data-autom="bag-item-totalprice" class="rs-iteminfo-price ">
<h3><?php echo "$".number_format($r["sub"],2);?></h3>
</p>
</div>
</div>
<!-- <div class="rs-iteminfo-pricelineitemwrapper">
<div class="rs-summary-content rs-summary-iteminfoactions">
<div class="rs-summary-labelandvaluecontainer">
<div data-autom="bagrs-summary-iteminfoactionslabel" class="rs-summary-label">
<div class="rs-iteminfo-actions-left">


</div>
</div>
<div data-autom="bagrs-summary-iteminfoactionsvalue" class="rs-summary-value">
<div class="rs-iteminfo-actions-right">
<button data-autom="bag-item-remove-button" type="button" class=" as-buttonlink rs-iteminfo-remove">
<span>
<span>Remove</span>
</span>
</button>
</div>
</div>

</div>
</div>
</div> -->


<div class= "remove" style="margin-top: 150;margin-left: -60px;color: #0E6CCD;" >	
	<!-- <form method="POST"> -->   

		<input type="hidden" name="cartId"   value="<?php echo $r["id"];?>"/>
		<input type="hidden" name="prod_quantity"   value="<?php echo $r["prod_quantity"];?>"/ >
		<input type="hidden" name="name"   value="<?php echo $r["name"];?>"/>
		
		
                <input type="submit"  name="update"      style="width:65px;  margin:4px 4px 4px 4px; padding:6px 6px 6px 6px; border-radius:15px;"        value="Update"/>
                <input type="submit"  name="delete"      style="width:65px;  margin:4px 4px 4px 4px; padding:6px 6px 6px 6px;border-radius:15px; "            value="Remove Item"/> 
		
	</form>      	
</div>


</div>
</div>

<hr style="width:1000px;margin-left:20%">
            <?php endforeach;?>






</div>
















<div style="margin-left:20%;display: inline-flex;">

<a href="https://web.njit.edu/~mb822/ITGAME/project/checkout.php">
	<?php 
		$_SESSION["directfromcart"] = "true";
		$_SESSION["currentcartitems"]= $results;
	?> 
	<input type="submit" name="saved" style="width: 400;margin-left: 350;border-radius: 30px;"  value="Checkout"/>
<a>




	<form method="POST" style="margin-left:0px" >
	<!--	<input type="submit" name="saved" style="width: 600;margin-left: 350;"  value="Checkout"/> -->
		<input type="submit" class="btn btn-danger" style="background-color:red;width:100px;margin-left:920px; padding: 4px 4px 4px 4px; border-radius: 15px; width: 100px;margin-left: 100px;padding: 7px 7px 7px 7px;border-radius: 15px;height: 35;margin-top: 35;margin-left: 180;"  name="clear" value="Clear Bag"/>
	</form>
</div>



	<hr style="width:1000px;margin-left:20%">

	<?php echo "Subtotal: "."$".number_format($tot,2);?>
        <?php echo "Tax: ". "$".number_format($tot*0.07,2);?>
	<?php echo "Total: "."$".(number_format($tot*1.07,2));?>

        <?php else:?>
        <div class="list-group-item"   style="background-color: #ffffff; margin-left:20%;"  >
            No items in cart
        </div>
        <?php endif;?>
        </div>
    </div>
<?php require(__DIR__ . "/partials/flash.php")?>

