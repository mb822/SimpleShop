<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
//we'll put this at the top so both php block have access to it
if(isset($_GET["id"])){
	$id = $_GET["id"];
}
?>
<?php
//saving
if(isset($_POST["save"])){
	//TODO add proper validation/checks

        $name = $_POST["name"];
        $quantity = $_POST["quantity"];
        $price = $_POST["price"];
        $description = $_POST["description"];
        $user = get_user_id();
	$category = $_POST["category"];
	$visible = $_POST["visible"];

	$checkout_img = $_POST["checkout_img"];

        $db = getDB();
//	$visibility = 0;
//	flash();
//	if($visible){visibility = 1;}
	
	if(isset($id)){
		$stmt = $db->prepare("UPDATE Products set  checkout_img=:checkout_img , name=:name,visibility=:visibility, quantity=:quantity, price=:price, description=:description,category=:category,  user_id=:user where id=:id");
		//$stmt = $db->prepare("UPDATE Products (name, quantity, price, description, user_id) VALUES(:name, :quantity, :price , :description, :user), where id=:id");
		//$stmt = $db->prepare("INSERT INTO Products  (name, quantity, price, description, user_id) VALUES(:name, :quantity, :price , :description, :user)");
		$r = $stmt->execute([
			":checkout_img"=>$checkout_img,
                	":name"=>$name,
                	":quantity"=>$quantity,
                	":price"=>$price,
                	":description"=>$description,
                	":user"=>$user,
			":id"=>$id,
			":category"=>$category,
			":visibility"=>$visible
		]);
		if($r){
			flash("Updated successfully with id: " . $id);
		}
		else{
			$e = $stmt->errorInfo();
			flash("Error updating: " . var_export($e, true));
		}
	}
	else{
		flash("ID isn't set, we need an ID in order to update");
	}
}
?>
<?php
//fetching
$result = [];
if(isset($id)){
	$id = $_GET["id"];
	$db = getDB();
	$stmt = $db->prepare("SELECT * FROM Products where id = :id");
	$r = $stmt->execute([":id"=>$id]);
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<form method="POST">

        <label for= "pleasesignin">Edit Product.</label>
	
	
	
        <input type="text" name="name" placeholder="Name" value="<?php echo $result["name"];?>" />
      	
	
	<input type="number" min="0" name="quantity" placeholder="Quantity" value="<?php echo $result["quantity"];?>" />
        
	
	<input type="float" min="0.00" name="price" placeholder="Price" value="<?php echo $result["price"];?>"  />


<!-- <input type="checkbox" if="visible" name="visible" value="<?php echo $result["visibility"];?>"      /><label for="visible">Visible?</label>
-->

<input type="number"  name="visible" placeholder="Visibility" value="<?php echo $result["visibility"];?>"  />

<input type="text"  name="category" placeholder="Category" value="<?php echo $result["category"];?>"  />
        
	
	<input type="text" name="description" placeholder="Description" value="<?php echo $result["description"];?>"  />
	
	<input type="text" name="checkout_img" placeholder="Checkout Image" value="<?php echo $result["checkout_img"];?>"  />

        <input type="submit" name="save" value="Submit Changes"/>






     







</form>


<?php require(__DIR__ . "/partials/flash.php");
