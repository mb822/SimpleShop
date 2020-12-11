<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
  //  flash("You don't have permission to access this page");
  //  die(header("Location: login.php"));
//}
?>
<?php
//we'll put this at the top so both php block have access to it
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>
<?php
//fetching
$result = [];
if (isset($id)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT checkout_img, id, name, quantity, price, description, user_id FROM Products WHERE id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
}
?>
<?php if (isset($result) && !empty($result)): ?>
    <div class="card">
        <div class="card-title">
          <!--  <?php safer_echo($result["name"]); ?> -->
        </div>
        <div class="card-body">
            <div>


		<div style="display:flex">
                	<h2><?php safer_echo($result["name"]); ?></h2>
		<!--	<form style="margin-left:60px"  action='https://web.njit.edu/~mb822/ITGAME/project/add_to_cart.php?id=<?php safer_echo($result['id']); ?>'>
    			<input type="submit" style="font-size:1.1em; border-radius:20px; font-weight:400; display:revert;margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px; width:250px"   value="Add to Bag" >
			</form>   -->
		
			<a href="https://web.njit.edu/~mb822/ITGAME/project/add_to_cart.php?id=<?php safer_echo($result['id']); ?>" style="margin-left:60px"  >
				<input type="submit" style="font-size:1.1em; border-radius:20px; font-weight:400; display:revert;margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px; width:250px"   value="Add to Bag" >
			</a>
			
		</div>



                <h6>$<?php safer_echo(number_format($result["price"],2)); ?></h6>
                <h6><?php safer_echo($result["description"]); ?></h6>
		

                        <?php if (has_role("Admin")): ?>
                                <a type="button" href="test_edit_product.php?id=<?php safer_echo($result['id']); ?>">Edit</a>
                        	<div></div>
			<?php endif; ?>

                        





                       <!-- <a type="button" style="font-size:1.5em; font-weight:400"   href="add_to_cart.php?id=<?php safer_echo($result['id']); ?>">Add to bag</a> -->
		
		<div></div>

		<img aria-hidden="true" style="margin-top: 0px;"   src="<?php echo $result["checkout_img"]?>" width="600" height="720" alt="" class="ir">
			

            <!--    <div>Quantity: <?php safer_echo($result["quantity"]); ?></div>    -->
           <!--     <div>Created by: <?php safer_echo($result["user_id"]); ?></div>   -->
            </div>
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
<?php require(__DIR__ . "/partials/flash.php");
