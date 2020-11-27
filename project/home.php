<?php require_once(__DIR__ . "/partials/nav.php"); ?>

	<div class="conatiner">

		<div class="centered">iPhone 12 Pro</div>

		<div class="info">
                <a type="button" class="info_page" href="test_view_products.php?id=16">Learn More ></a>
                <a type="button" class="add_to_cart" href="test_view_products.php?id=3">Add to bag ></a>
		</div>

	<!--	<img class="picture-image" src="https://www.apple.com/newsroom/images/product/iphone/standard/Apple_announce-iphone12pro_10132020_big.jpg.large.jpg" srcset="https://www.apple.com/newsroom/images/product/iphone/standard/Apple_announce-iphone12pro_10132020_big.jpg.large_2x.jpg 2x" alt="iPhone 12 Pro and iPhone 12 Pro Max."> -->
		<img class="picture-image" src="home12pro.png"  alt="iPhone 12 Pro and iPhone 12 Pro Max.">
				
	</div>
<?php
//we use this to safely get the email to display
$email = "";
if (isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])) {
    $email = $_SESSION["user"]["email"];
flash("Welcome, ".$email);
}
//else{flash("Welcome");}



?>
  <!--  <p>Welcome, <?php echo $email; ?></p> -->
<?php require(__DIR__ . "/partials/flash.php");


