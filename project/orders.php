
<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
  //  flash("You don't have permission to access this page");
  //  die(header("Location: login.php"));
//}

?>
<?php


//admin search
//pagination
$page = 1;
$per_page = 10;
if(isset($_GET["page"])){
    try {
        $page = (int)$_GET["page"];
    }
    catch(Exception $e){

    }
}

    $db = getDB();
    $user = get_user_id();

if(has_role("Admin")){
$stmt = $db->prepare("SELECT count(*) as total from Orders");

}
else{
$stmt = $db->prepare("SELECT count(*) as total from Orders WHERE user_id=:id ");
}
$stmt->execute([":id"=>get_user_id()]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total = 0;
if($result){
    $total = (int)$result["total"];
}
$total_pages = ceil($total / $per_page);
$offset = ($page-1) * $per_page;
/////////pagination


    if(has_role("Admin")){
	//kinda useless parameter added to sql - doesnt work without cause of bind below
        $stmt = $db->prepare("SELECT * from Orders     WHERE :user IS NOT NULL          LIMIT :offset, :count");
    }
    else{
        //SELECT OrderItems.*, Orders.user_id, Orders.address, Orders.total_price, Orders.payment_method FROM OrderItems JOIN Orders ON OrderItems.order_id = Orders.id
        $stmt = $db->prepare("SELECT * FROM Orders WHERE user_id = :user LIMIT :offset, :count");
    }
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
$stmt->bindValue(":user", get_user_id());
$r = $stmt->execute();


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


<a href = "view_order.php?id=<?php safer_echo($r['id']); ?>&user_id=<?php echo $r["user_id"] ;?> " style="text-decoration:none; margin-bottom:10px;">
<div class='col-lg-2'>
<div class='item'>

                <div class="list-group-item" style="width: 500px;" >
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



<!-pagination stuff -->
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($page-1) < 1?"disabled":"";?>">
                    <a class="page-link" href="?page=<?php echo $page-1;?>" tabindex="-1">Previous</a>
                </li>
                <?php for($i = 0; $i < $total_pages; $i++):?>
                <li class="page-item <?php echo ($page-1) == $i?"active":"";?>"><a class="page-link" href="?page=<?php echo ($i+1);?>"><?php echo ($i+1);?></a></li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($page) >= $total_pages?"disabled":"";?>">
                    <a class="page-link" href="?page=<?php echo $page+1;?>">Next</a>
                </li>
            </ul>
        </nav>






    <?php else: ?>
        <p class="no_results">No previous orders found.</p>
    <?php endif; ?>
</div>







  
