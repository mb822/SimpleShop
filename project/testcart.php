
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

        <?php if($results && count($results) > 0):?>
    














    






            <?php foreach($results as $r):?>
            <div class="cart_item_div">

    <img aria-hidden="true" data-scale-params-2="wid=800&amp;hei=800&amp;fmt=jpeg&amp;qlt=95&amp;op_usm=0.5,1.5&amp;fit=constrain&amp;.v=1598653759000" src="https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/ipad-air-select-wifi-gold-202009_FMT_WHH?wid=400&amp;hei=400&amp;fmt=jpeg&amp;qlt=95&amp;op_usm=0.5,1.5&amp;fit=constrain&amp;.v=1598653759000" width="200" height="200" alt="" class="ir">




        <div class="rs-iteminfo-details" style="display:flex;">
	<div class="large-6 rs-iteminfo-title-wrapper small-12 "  style="min-width:350px; width:600px;"  >
            
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
		<input type="number" min="0" name="quantity" value="<?php echo $r["quantity"];?>"  style="margin: 10px 11%;padding: 6px 12px; width:65px;border-radius: 20px;height: 40px; "  >
	<!-- </form> -->



<div class="large-last rs-iteminfo-pricedetails" style="width:200px">
<div>
<p data-autom="bag-item-totalprice" class="rs-iteminfo-price ">
<h3><?php echo "$".$r["sub"];?></h3>
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
                <input type="submit"  name="update"      style="width:65px;  margin:4px 4px 4px 4px; padding:6px 6px 6px 6px; border-radius:15px;"        value="Update"/>
                <input type="submit"  name="delete"      style="width:65px;  margin:4px 4px 4px 4px; padding:6px 6px 6px 6px;border-radius:15px; "            value="Remove Item"/> 
		
	</form>      	
</div>


</div>
</div>

<hr style="width:1000px;margin-left:20%">
            <?php endforeach;?>






</div>



















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

