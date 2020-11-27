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
//fetching
$result = [];
if (isset($id)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT id, name, quantity, price, description, user_id FROM Products WHERE id = :id");
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
                <h2><?php safer_echo($result["name"]); ?></h2>
                <h6>$<?php safer_echo(number_format($result["price"]), 2); ?></h6>
                <h6><?php safer_echo($result["description"]); ?></h6>
            <!--    <div>Quantity: <?php safer_echo($result["quantity"]); ?></div>    -->
           <!--     <div>Created by: <?php safer_echo($result["user_id"]); ?></div>   -->
            </div>
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
<?php require(__DIR__ . "/partials/flash.php");
