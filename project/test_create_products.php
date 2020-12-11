<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>

<form method="POST">

        <label for= "pleasesignin">Add Product.</label>
        <input type="text" name="name" placeholder="Name"/>
        <input type="number" min="0" name="quantity" placeholder="Quantity"/>
        <input type="float" min="0.00" name="price" placeholder="Price"/>
        <input type="text" name="description" placeholder="Description"/>
	<input type="text" name="category" placeholder="Category"/>
	
	<input type="test" name="checkout_img" placeholder="Checkout Image" >
	
	<input type="checkbox" if="visible" name="visible" value="1"/><label for="visible">Visible?</label>

        <input type="submit" name="save" value="Create"/>
</form>

<?php
if(isset($_POST["save"])){
        //TODO add proper validation/checks
        $name = $_POST["name"];
        $quantity = $_POST["quantity"];
        $price = $_POST["price"];
        $description = $_POST["description"];
        $user = get_user_id();
	$category = $_POST["category"];
	$visible = isset($_POST["visible"]);

	$checkout_img = $_POST["checkout_img"];

	$visibility = 0;

	if($visible){$visibility = 1;}
//	else{$visibility = 0;}
	
	flash(var_dump($visible));
	
        $db = getDB();

        $stmt = $db->prepare("INSERT INTO Products  (checkout_img, name, quantity, price, description, user_id, category, visibility) VALUES(:checkout_img, :name, :quantity, :price , :description, :user, :category, :visibility)");
        $r = $stmt->execute([
		":checkout_img"=>$checkout_img,
                ":name"=>$name,
                ":quantity"=>$quantity,
                ":price"=>$price,
                ":description"=>$description,
                ":user"=>$user,
		":category"=>$category,
		":visibility"=>$visibility
        ]);
	if($r){
               	flash("Created successfully with id: " . $db->lastInsertId());
        }
	else{
             	$e = $stmt->errorInfo();
                flash("Error creating: " . var_export($e, true));
        }
}
?>
<?php require(__DIR__ . "/partials/flash.php");

