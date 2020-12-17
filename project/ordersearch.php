<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {

    flash("You don't have permission to access this page.");
    die(header("Location: login.php"));
}
?>

<?php
//************************************************************
$results = [];
$category = "";
$startdate = "";
$enddate = "";


if(isset($_SESSION["category"])){$category = $_SESSION["category"];}
if(isset($_SESSION["startdate"])){$startdate = $_SESSION["startdate"];}
if(isset($_SESSION["enddate"])){$enddate = $_SESSION["enddate"];}

if(isset($_POST["category"]) && $_POST["category"] != $category  ){$category = $_POST["category"];}
if(isset($_POST["startdate"]) && $_POST["startdate"] != $startdate  ){$startdate = $_POST["startdate"];}
if(isset($_POST["enddate"]) && $_POST["enddate"] != $enddate  ){$enddate = $_POST["enddate"];}


$_SESSION["category"] = $category;
$_SESSION["startdate"] = $startdate;
$_SESSION["enddate"] = $enddate;

if($startdate == ""){
	$tempstartdate = "0000-01-01";
}
else{$tempstartdate = $startdate;}
if($enddate == ""){ 
        $tempenddate = "9999-12-31";
}
else{$tempenddate = $enddate;}

//echo $tempstartdate."<br>";
//echo $tempenddate."<br>";
//echo $category."<br>";



//*********************************************************
$page = 1;
$per_page = 10;
if(isset($_GET["page"])){try {$page = (int)$_GET["page"];}catch(Exception $e){;}}
$db = getDB();

if($category == ""){
	$stmt = $db->prepare("SELECT count(*) as total, SUM(OrderItems.quantity*OrderItems.unit_price) as net  FROM (Orders JOIN OrderItems ON Orders.ID = OrderItems.order_id) JOIN Products ON OrderItems.product_id = Products.id WHERE :category='' AND (Orders.created BETWEEN :startdate AND :enddate)");
}
else{
	$stmt = $db->prepare("SELECT count(*) as total, SUM(OrderItems.quantity*OrderItems.unit_price) as net  FROM (Orders JOIN OrderItems ON Orders.ID = OrderItems.order_id) JOIN Products ON OrderItems.product_id = Products.id WHERE Products.category = :category AND (Orders.created BETWEEN :startdate AND :enddate)");
}

//$stmt->execute([":q" => "%$query%", ":category" => "$query" ]);
$stmt->bindValue(":enddate", $tempenddate);
$stmt->bindValue(":startdate", $tempstartdate);
$stmt->bindValue(":category", $category);
$r = $stmt->execute();

$res = $stmt->fetch(PDO::FETCH_ASSOC);
$total = 0;
if($res){$total = (int)$res["total"];}
$total_pages = ceil($total / $per_page);
$offset = ($page-1) * $per_page;
$net = $res["net"];
//************************************************************************





    

//if($category != ""){

if($category == ""){
	$stmt = $db->prepare("SELECT Products.name,OrderItems.* FROM (Orders JOIN OrderItems ON Orders.ID = OrderItems.order_id) JOIN Products ON OrderItems.product_id = Products.id WHERE :category=''  AND (Orders.created BETWEEN :startdate AND :enddate)  LIMIT :offset, :count");
}
else{
$stmt = $db->prepare("SELECT Products.name,OrderItems.* FROM (Orders JOIN OrderItems ON Orders.ID = OrderItems.order_id) JOIN Products ON OrderItems.product_id = Products.id WHERE Products.category = :category AND (Orders.created BETWEEN :startdate AND :enddate)  LIMIT :offset, :count");
}

$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
$stmt->bindValue(":enddate", $tempenddate);
$stmt->bindValue(":startdate", $tempstartdate);
$stmt->bindValue(":category", $category);

    $r = $stmt->execute();

	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//}












//on click search
if (isset($_POST["search"]) && !empty($query)    ) {
    $db = getDB();
	
if($category == ""){
	$stmt = $db->prepare("SELECT Products.name,OrderItems.* FROM (Orders JOIN OrderItems ON Orders.ID = OrderItems.order_id) JOIN Products ON OrderItems.product_id = Products.id WHERE :category='' AND (Orders.created BETWEEN :startdate AND :enddate)  LIMIT :offset, :count");
}
else{
	$stmt = $db->prepare("SELECT Products.name,OrderItems.* FROM (Orders JOIN OrderItems ON Orders.ID = OrderItems.order_id) JOIN Products ON OrderItems.product_id = Products.id WHERE Products.category = :category AND (Orders.created BETWEEN :startdate AND :enddate)  LIMIT :offset, :count");
}


$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
$stmt->bindValue(":enddate", $tempenddate);
$stmt->bindValue(":startdate", $tempstartdate);
$stmt->bindValue(":category", $category);
    
    $r = $stmt->execute();

    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results");
    }
}
?>
<form method="POST">
    <label for= "pleasesignin">Search Purchase History.</label>

    <input type="text" name="category" placeholder="Category" value="<?php safer_echo($category); ?>"/>
<date style="display:flex;margin-top: -10px;">
    <input type="date" style="display:flex;width:170px;margin-left:11%;margin-right:10px"  name="startdate" placeholder="Start Date: YYYY-MM-DD" value="<?php safer_echo($startdate); ?>"/>
    <input type="date" style="display:flex;margin-left:0px; width:170px;"  name="enddate" placeholder="End Date: YYYY-MM-DD" value="<?php safer_echo($enddate); ?>"/>
</date>
    <input type="submit" value="Search" name="search"/>
</form>
<div class="results">


    <?php if (count($results) > 0): ?>
	
	<!--<?php
	    $total = 0;
	    foreach($results as $r){
		$total+=($r["quantity"]*$r["unit_price"]);
	    }
	?>-->



        <div class="list-group">






<?php
    $i = 0;
?>

<div>
    <label for= "pleasesignin" style="margin-left:0px">Total Sales $<?php echo (number_format($net*1.07, 2) )  ;?>  </label>
</div>

<div class="row">
<?php foreach ($results as $r): ?>
<?php $i++; ?>



<div class='col-lg-2'>
<div class='item'>
                <div class="list-group-item">
                    <div>
                        <div><?php safer_echo("Order#: ".$r["order_id"]); ?></div>
                    </div>
                    <div>
                        <div><?php safer_echo($r["name"]); ?></div>
                    </div>
                    <div>
                      <div><?php safer_echo("$".$r["unit_price"]."   X   ".$r["quantity"]); ?></div>
                    </div>
                    <div>
                      <div><?php safer_echo("Subtotal $: ".($r["unit_price"]*$r["quantity"])   ); ?></div>
                    </div>
                </div>
</div>
</div>
<?php endforeach; ?>


        </div>


        <nav>
            <ul class="pagination justify-content-center" style="margin-left: -15%;margin-top: 40px;">
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
        <p class="no_results">No orders found.</p>
    <?php endif; ?>
</div>

<label style="margin-top:300px;"></label>

<?php require_once(__DIR__ . "/partials/flash.php"); ?>  
