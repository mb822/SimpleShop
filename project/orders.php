
<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
  //  flash("You don't have permission to access this page");
  //  die(header("Location: login.php"));
//}

?>
<?php

    $db = getDB();
    $user = get_user_id();

    if(has_role("Admin")){
	    //Added admin purchase history
        $stmt = $db->prepare("SELECT * from Orders LIMIT 10");
    }
    else{
        //SELECT OrderItems.*, Orders.user_id, Orders.address, Orders.total_price, Orders.payment_method FROM OrderItems JOIN Orders ON OrderItems.order_id = Orders.id
        $stmt = $db->prepare("SELECT * FROM Orders WHERE user_id = :user LIMIT 10");
    }


    $r = $stmt->execute([":user" => $user]);
    if ($r) {
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
	flash("There was a problem fetching the orders.");
    }

?>


<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">






<?php
    $i = 0;
?>

<label for="pleasesignin" style="margin-left:0px;" >Past Orders.</label>

<div class="row">
<?php foreach ($results as $r): ?>
<?php $i++; ?>


<a href = "view_order.php?id=<?php safer_echo($r['id']); ?>" style="text-decoration:none; margin-bottom:10px;">
<div class='col-lg-2'>
<div class='item'>

                <div class="list-group-item" style="width: 600px;" >
                    <div>
                       <!-- <div>Name:</div>-->
                        <div><?php safer_echo( "Order #: ".$r["id"]); ?></div>
                    </div>

                        <!--<div>
                             	<img aria-hidden="true"  src="<?php echo $r["checkout_img"]?>" width="200" height="240" alt="" class="ir">
                        </div> -->


                    <!--<div>
                        <div><?php safer_echo("Order #: $".$r["id"]); ?></div>
                    </div>-->
                    <div>
                        <div><?php safer_echo("Total price: $".$r["total_price"]); ?></div>
                    </div>
                    <div>
                        <div><?php safer_echo("Shipping Address: ".$r["address"]); ?></div>
                    </div>
                    <div>
                        <div><?php safer_echo("Purchased on: ".$r["created"]); ?></div>
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
</div>
</a>

<?php endforeach; ?>







        </div>
    <?php else: ?>
        <p class="no_results">No previous orders found.</p>
    <?php endif; ?>
</div>







  
