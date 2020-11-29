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
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>
<?php
//saving

// if(isset($_POST["delete"])){
//     $db = getDB();
//     if (isset($id)) {

//         $sql = "DELETE FROM Carts WHERE id = $id";
//         $r = $db->query($sql);

//         ]);
// 	if ($r) {
//             flash("Deleted successfully with id: " . $id);
//         }
// 	else {
//             flash("Error deleting.");
//         }
//     }
//     else {
// 	flash("ID isn't set, we need an ID in order to delete.");
//     }
// }


if (isset($_POST["save"])) {
    //TODO add proper validation/checks
    $quantity = $_POST["quantity"];
    $product_id = $_POST["product_id"];
    $user_id = $_POST["user_id"];
    $price = $_POST["price"];
    //$user = get_user_id();
    $db = getDB();
    if (isset($id)) {
        $stmt = $db->prepare("UPDATE Cart set quantity=:quantity, product_id=:product_id, user_id=:user_id, price=:price where id=$id");
        $r = $stmt->execute([
            ":quantity" => $quantity,
            ":product_id" => $product_id,
            ":user_id" => $user_id,
            ":price" => $price
        ]);
	if ($r) {
            flash("Updated successfully with id: " . $id);
        }
	else {
            $e = $stmt->errorInfo();
            flash("Error updating: " . var_export($e, true));
        }
    }
    else {
	flash("ID isn't set, we need an ID in order to update");
    }
}
?>
<?php
//fetching
$result = [];
if (isset($id)) {
    $id = $_GET["id"];
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM Cart where id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
//get eggs for dropdown
$db = getDB();
$stmt = $db->prepare("SELECT id,name from Products LIMIT 10");
$r = $stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <label for= "pleasesignin">Edit Cart.</label>
    <form method="POST">
        <label>Cart ID:</label>
        <input name="cart_id" placeholder="ID" value="<?php echo $result["id"]; ?>"/>
        <label>Product ID:</label>
<select name="product_id" value="<?php echo $result["product_id"];?>" >
            <option value="-1">None</option>
            <?php foreach ($products as $product): ?>
                <option value="<?php safer_echo($product["id"]); ?>" <?php echo ($result["product_id"] == $product["id"] ? 'selected="selected"' : ''); ?>
                ><?php safer_echo($product["name"]); ?></option>
            <?php endforeach; ?>
</select>
        <label>Quantity:</label>
        <input type="number" min="1" name="quantity" value="<?php echo $result["quantity"]; ?>"/>
        <label>User ID:</label>
        <input type="number" name="user_id" value="<?php echo $result["user_id"]; ?>"/>
        <label>Price:</label>
        <input type="number" min="0.00" step="0.01" name="price" value="<?php echo $result["price"]; ?>"/>
        <input type="submit" name="save" value="Update"/>
        <input type="submit" name="delte" value="Delete"/>
    </form>


<?php require(__DIR__ . "/partials/flash.php");
