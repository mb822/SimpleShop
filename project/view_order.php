<?php require_once(__DIR__ . "/partials/nav.php"); ?>


<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("Please login to view past orders.");
    die(header("Location: login.php"));
}



if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>


<?php

$result = [];
if (isset($id)) {
    $db = getDB();
	 
	$user_id = get_user_id();
	if(     has_role("Admin")     ){
		$user_id = $_GET["user_id"];
	}


    $stmt = $db->prepare("SELECT OrderItems.*, Products.name FROM Products JOIN (OrderItems JOIN Orders ON Orders.id = OrderItems.order_id) ON Products.id = OrderItems.product_id WHERE Orders.user_id = :user AND Orders.id = :id");
    $r = $stmt->execute([":id" => $id, ":user" => $user_id]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$results) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
}
?>



















<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">






<?php
    $i = 0;
?>

<label for="pleasesignin" style="margin-left:0px;" >Order #<?php echo $results[0]['order_id']  ;?></label>

<div class="row">
<?php foreach ($results as $r): ?>
<?php $i++; ?>



<div class='col-lg-2'>
<div class='item'>

	<div class = "item_info">
                <div class="list-group-item" style="width: 600px;" >
                    <div>
                       <!-- <div>Name:</div>-->
                        <div><?php safer_echo( "Item: ".$r["name"]); ?></div>
                    </div>

                        <!--<div>
                                <img aria-hidden="true"  src="<?php echo $r["checkout_img"]?>" width="200" height="240" alt="" class="ir">
                        </div> -->


                    <!--<div>
                        <div><?php safer_echo("Order #: $".$r["id"]); ?></div>
                    </div>-->
                    <div>
                        <div><?php safer_echo("Quantity: ".$r["quantity"]); ?></div>
                    </div>
                    <div>
                        <div><?php safer_echo("Unit Price: $ ".$r["unit_price"]); ?></div>
                    </div>
                    <div>
                        <div><?php safer_echo("Subtotal: $ ".$r["unit_price"]*$r["quantity"]    ); ?></div>
                    </div>


                    <div>

                       <!-- <?php if (has_role("Admin")): ?>
                                <a type="button" href="test_edit_product.php?id=<?php safer_echo($r['id']); ?>">Edit</a>|
                        <?php endif; ?> -->

                       <!-- <a type="button" href="test_view_products.php?id=<?php safer_echo($r['id']); ?>">View</a>|
                        <a type="button" href="add_to_cart.php?id=<?php safer_echo($r['id']); ?>">Add to bag</a> -->
                    </div>
                </div>
	</div>

	<div class="item_image">
	</div>


</div>
</div>


<?php endforeach; ?>







        </div>
    <?php else: ?>
        <p class="no_results">No previous orders found.</p>
    <?php endif; ?>
</div>




