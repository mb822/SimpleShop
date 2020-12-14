<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
  //  flash("You don't have permission to access this page");
  //  die(header("Location: login.php"));
//}
?>
<?php
$query = "";
$results = [];
$sort = "";

if(isset($_POST["sort_by"])){
    $sort = $_POST["sort_by"];
}

if (isset($_POST["query"])) {
    $query = $_POST["query"];
}
else{
    $db = getDB();
    $stmt = $db->prepare("SELECT checkout_img, id, name, quantity, price, description, user_id from Products WHERE category = 'iphone'");
    $r = $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}




if (isset($_POST["search"])) { //!empty($query)
    $db = getDB();

   if(empty($query)){
       $query = "iphone";
   }





///////////////////////////pagination

//$page = 1;
//$per_page = 10;
//if(isset($_GET["page"])){
//    try {
//        $page = (int)$_GET["page"];
//    }
//    catch(Exception $e){}
//}
//$stmt = $db->prepare("SELECT count(*) as total from Products WHERE (name like :q OR category = :category) AND visibility = 1");
//$stmt->execute([":q" => "%$query%", ":category" => "$query" ]);
//$result = $stmt->fetch(PDO::FETCH_ASSOC);
//$total = 0;
//if($result){
//    $total = (int)$result["total"];
//}
//$total_pages = ceil($total / $per_page);
//$offset = ($page-1) * $per_page;



/////////////////////////pagination




    if(has_role("Admin")){
	if($query == "ALL"){$stmt = $db->prepare("SELECT checkout_img, id, name, quantity, price, description, user_id from Products");}
    	else{$stmt = $db->prepare("SELECT checkout_img, id, name, quantity, price, description, user_id from Products WHERE name like :q OR category=  :q");}
    }
    else{
//	flash(var_dump($sort) );
//	flash("first if:");
//	flash(var_dump(strcmp($sort, "hl")==0));
//	flash("Second if:");
//	flash(var_dump(strcmp($sort, "lh")==0));
	
//	flash(var_dump($sort=="0"));	
//


	if(strcmp($sort, "lh")==0){    $stmt = $db->prepare("SELECT checkout_img,id, name, quantity, price, description, user_id from Products WHERE (name like :q OR category = :category) AND visibility = 1  ORDER BY price ASC   LIMIT 10");}
	elseif(strcmp($sort, "hl")==0){$stmt = $db->prepare("SELECT checkout_img, id, name, quantity, price, description, user_id from Products WHERE (name like :q OR category = :category) AND visibility = 1  ORDER BY price DESC   LIMIT 10");}
	else{$stmt = $db->prepare("SELECT checkout_img, id, name, quantity, price, description, user_id from Products WHERE (name like :q OR category = :category) AND visibility = 1 LIMIT 10");}
    }


    $r = $stmt->execute([":q" => "%$query%", ":category" => "$query" ]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results");
    }
}
?>
<form method="POST">
    <label for= "pleasesignin">Search Inventory.</label>
    <input name="query" placeholder="Search by Name or Category" value="<?php safer_echo($query); ?>"/>
	 
	<select name="sort_by" id="sort_by"  value="<?php safer_echo($sort); ?>" >

	    <option value="0" <?php echo($sort== "0"?'selected="selected"': '');    ?>>Sort by: None</option>
	    <option value="hl" <?php echo($sort== "hl"?'selected="selected"': '');    ?>>Price: High to Low</option>
	    <option value="lh" <?php echo($sort== "lh"?'selected="selected"': '');    ?>>Price: Low to High</option>

	</select>	
	
    <input type="submit" value="Search" name="search"/>
</form>
<div class="results">

<?php if(!isset($_POST["query"])): ?>
<label for="pleasesignin" style="font-size: 1.2em;color: #3465b6; font-weight: 400; margin-top:15px">Popular Products.</label>
<?php endif; ?>

    <?php if (count($results) > 0): ?>



        <div class="list-group">






<?php
    $i = 0;
?>


<div class="row">
<?php foreach ($results as $r): ?>
<?php $i++; ?>



<div class='col-lg-2'>
<div class='item'>

                <div class="list-group-item">
                    <div>
                       <!-- <div>Name:</div>-->
                        <div><?php safer_echo($r["name"]); ?></div>
                    </div>

			<div>
				<img aria-hidden="true"  src="<?php echo $r["checkout_img"]?>" width="200" height="240" alt="" class="ir">
			</div>


                    <div>
                       <!-- <div>Price:</div>-->
                        <div><?php safer_echo("$".$r["price"]); ?></div>
                    </div>
                    <div>
                       <!-- <div>Description:</div>-->
          <!--              <div><?php safer_echo($r["description"]); ?></div>  -->
                    </div>   

                    <div>

			<?php if (has_role("Admin")): ?>
                        	<a type="button" href="test_edit_product.php?id=<?php safer_echo($r['id']); ?>">Edit</a>|
			<?php endif; ?>

                        <a type="button" href="test_view_products.php?id=<?php safer_echo($r['id']); ?>">View</a>|
			<a type="button" href="add_to_cart.php?id=<?php safer_echo($r['id']); ?>">Add to bag</a>
                    </div>
                </div>

</div>
</div>


<?php endforeach; ?>







<!-- for pagination
        <nav aria-label="My Eggs">
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
-->











        </div>
    <?php else: ?>
        <p class="no_results">No results</p>
    <?php endif; ?>
</div>

<label style="margin-top:300px;"></label>
