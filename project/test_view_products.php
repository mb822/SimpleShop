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
    $stmt = $db->prepare("SELECT average_rating, checkout_img, id, name, quantity, price, description, user_id FROM Products WHERE id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
    $name = $result["name"];
    $average_rating = $result["average_rating"];


                            if(round($average_rating) == 1){$average_rating = "★☆☆☆☆";}
                            elseif(round($average_rating) == 2){$average_rating = "★★☆☆☆";}
                            elseif(round($average_rating) == 3){$average_rating = "★★★☆☆";}
                            elseif(round($average_rating) == 4){$average_rating = "★★★★☆";}
			    elseif(round($average_rating) == 5){$average_rating = "★★★★★";}
                            
                        

}
?>
<?php if (isset($result) && !empty($result)): ?>



    <div class="card">
        <div class="card-title">
          <!--  <?php safer_echo($result["name"]); ?> -->
        </div>
        <div class="card-body">
            <div>


	<h2 style="color:#ff7d00;"><?php safer_echo($average_rating); ?></h2>

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
































<?php
//paginated reviews
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
$stmt = $db->prepare("SELECT count(*) as total from Ratings where product_id = :product_id");
$stmt->execute([":product_id"=>$id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total = 0;
if($result){
    $total = (int)$result["total"];
}
$total_pages = ceil($total / $per_page);
$offset = ($page-1) * $per_page;
$stmt = $db->prepare("SELECT Ratings.*,Users.username from Ratings JOIN Users ON Ratings.user_id = Users.id where product_id = :product_id LIMIT :offset, :count");
//need to use bindValue to tell PDO to create these as ints
//otherwise it fails when being converted to strings (the default behavior)
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
$stmt->bindValue(":product_id", $id);
$stmt->execute();
$e = $stmt->errorInfo();
if($e[0] != "00000"){
    flash(var_export($e, true), "alert");
}
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">


<label for="pleasesignin" style="margin-left:0px;" >Reviews.</label>

<div class="row">
<?php foreach ($results as $r): ?>
                        <?php
                            if($r["rating"] == 1){$rating = "★☆☆☆☆";}
                            elseif($r["rating"] == 2){$rating = "★★☆☆☆";}
                            elseif($r["rating"] == 3){$rating = "★★★☆☆";}
                            elseif($r["rating"] == 4){$rating = "★★★★☆";}
                            else{$rating = "★★★★★";}
                        ?>


<div class='col-lg-2'>
<div class='item'>

                <div class="list-group-item" style="width: 500px;background-color:#c9cfd2;border-radius:7px " >
                    <a href="profile.php?id=<?php echo $r[user_id];?>"><?php echo $r["username"];?></a>
		    <div>

                        <div style="color:#ff7d00"><?php safer_echo( $rating); ?></div>
                    </div>
                    <div>
                        <div style="word-wrap: break-word;"><?php safer_echo($r["comment"]); ?></div>
                    </div>
                </div>

</div>
</div>


<?php endforeach; ?>

        </div>
    <?php else: ?>
        <p class="no_results">No reviews found.</p>
    <?php endif; ?>
</div>





<?php if (count($results) > 0): ?>
        <nav>
            <ul class="pagination justify-content-center" style="margin-top: 40;margin-bottom: 60;">
                <li class="page-item <?php echo ($page-1) < 1?"disabled":"";?>">
                    <a class="page-link" href="?id=<?php echo $id;?>&page=<?php echo $page-1;?>">Previous</a>
                </li>
                <?php for($i = 0; $i < $total_pages; $i++):?>
                <li class="page-item <?php echo ($page-1) == $i?"active":"";?>"><a class="page-link" href="?id=<?php echo $id;?>&page=<?php echo ($i+1);?>"><?php echo ($i+1);?></a></li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($page) >= $total_pages?"disabled":"";?>">
                    <a class="page-link" href="?id=<?php echo $id;?>&page=<?php echo $page+1;?>">Next</a>
                </li>
            </ul>
        </nav>
<?php endif; ?>












<?php
    //check if user has purchased this product
    $purchased = 0;
    $stmt = $db->prepare("SELECT OrderItems.*, Products.name FROM Products JOIN (OrderItems JOIN Orders ON Orders.id = OrderItems.order_id) ON Products.id = OrderItems.product_id WHERE Orders.user_id = :user");
    $r = $stmt->execute([":user" => get_user_id()]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    foreach($results as $r){
        if(     $r["product_id"] == $id    ){$purchased = 1;}
    }
if(isset($_POST["save"])){
        $rating = 1;
        $comment = $_POST["comment"];
        $db = getDB();
	if(    array_key_exists("stars",$_POST)    ){$rating = $_POST["stars"];}

        $stmt = $db->prepare("INSERT INTO Ratings  (product_id, user_id, rating, comment) VALUES(:product_id, :user_id, :rating, :comment)");
        $r = $stmt->execute([
                ":product_id"=>$id,
		":user_id"=>get_user_id(),
                ":rating"=>$rating,
                ":comment"=>$comment
        ]);
	if($r){
               	flash("Thanks for writing a review!");
        }
	else{
             	$e = $stmt->errorInfo();
                flash("Issue with writing a review.". var_export($e, true));
        }


//add logic to update average_rating
$stmt = $db->prepare("SELECT AVG(rating) as rating FROM Ratings WHERE product_id = $id");
$r = $stmt->execute();
$average = ($stmt->fetch(PDO::FETCH_ASSOC))["rating"];


		$stmt = $db->prepare("UPDATE Products set average_rating=$average  where id=$id");
                $r = $stmt->execute();
}
?>










<?php if($purchased == 1): ?>
<form method="POST" style="margin-left:11%;display:grid;">
<h2 style="margin-bottom: 50px; margin-top:30px;"  >Write a Review for <?php safer_echo($name); ?>. </h2>
<div class="rating" style="margin-left:50px;    width: 250px;" >
  <label>
    <input type="radio" name="stars" value="1" />
    <span class="icon">★</span>
  </label>
  <label>
    <input type="radio" name="stars" value="2" />
    <span class="icon">★</span>
    <span class="icon">★</span>
  </label>
  <label>
    <input type="radio" name="stars" value="3" />
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>   
  </label>
  <label>
    <input type="radio" name="stars" value="4" />
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
  </label>
  <label>
    <input type="radio" name="stars" value="5" />
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
  </label>
</div>
<textarea id="comment" name="comment" cols="45" rows="8" maxlength="289"  placeholder="  Comment" style="margin-left:0px;height: 150px;width:350px;margin-top:40px;" ></textarea>
<input type="submit" name="save" value="Submit" style="margin-left:0px" />
</form>
<?php endif; ?>







<?php require(__DIR__ . "/partials/flash.php");
