<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
$query = "";
$results = [];
if (isset($_POST["query"])) {
    $query = $_POST["query"];
}
if (isset($_POST["search"]) && !empty($query)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT id, product_id, price, user_id from Cart WHERE user_id = :query OR product_id = :query LIMIT 10");
    $r = $stmt->execute([":query" => "$query"]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results " . var_export($stmt->errorInfo(), true));
    }
}
?>
<label for= "pleasesignin">Search User Carts.</label>
<form method="POST">
    <input name="query" placeholder="Search by User ID or Product ID" value="<?php safer_echo($query); ?>"/>
    <input type="submit" value="Search" name="search"/>
</form>
<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $r): ?>
                <div class="list-group-item">
                    <div>
                        <div>User ID:</div>
                        <div><?php safer_echo($r["user_id"]); ?></div>
                    </div>
                    <div>
                        <div>Product ID:</div>
                        <div><?php safer_echo($r["product_id"]); ?></div>
                    </div>
                    <div>
                        <div>Price:</div>
                        <div><?php safer_echo($r["price"]); ?></div>
                    </div>
                    <div>
                        <a type="button" href="test_edit_cart.php?id=<?php safer_echo($r['id']); ?>">Edit</a>
                        <a type="button" href="test_view_cart.php?id=<?php safer_echo($r['id']); ?>">View</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div> 
