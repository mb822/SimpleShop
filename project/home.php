<?php require_once(__DIR__ . "/partials/nav.php"); ?>

	<div class="conatiner">

		<div class="centered">! iPhone 12 Pro</div>
		
		<h3 class="subhead" role="presentation">It’s a leap year.</h3>

		<div class="pricing-info">
			<p class="price price-with-carrier">
			<span style="font-weight: 300;"   aria-label="From forty one dollars and sixty two cents per month or nine hundred ninety nine dollars before trade in." role="text">From $54.13/mo. or $1299 before trade‑in.</span> <!-- <sup><a href="#footnote-1" class="footnote">1</a></sup> -->
			</p>
			<p  style="font-weight: 300;"     class="carrier">Buy directly from Apple with special carrier offers.</p>
		</div>	
	
		<div class="info">
                <a type="button" class="info_page"  style="font-weight: 200;"     href="test_view_products.php?id=16">Learn More ></a>
                <a type="button" class="add_to_cart"  style="font-weight: 200;"    href="add_to_cart.php?id=16">Add to bag ></a>
		</div>

	<!--	<img class="picture-image" src="https://www.apple.com/newsroom/images/product/iphone/standard/Apple_announce-iphone12pro_10132020_big.jpg.large.jpg" srcset="https://www.apple.com/newsroom/images/product/iphone/standard/Apple_announce-iphone12pro_10132020_big.jpg.large_2x.jpg 2x" alt="iPhone 12 Pro and iPhone 12 Pro Max."> -->
		<img class="picture-image" src="home12pro.png"  alt="iPhone 12 Pro and iPhone 12 Pro Max.">






                <div class="centered_inverted">! iPhone 12</div>

                <div class="info_inverted">
                <a type="button" class="info_page_inverted"    style="font-weight: 200;"    href="test_view_products.php?id=28">Learn More ></a>
                <a type="button" class="add_to_cart_inverted"  style="font-weight: 200;"       href="add_to_cart.php?id=28">Add to bag ></a>
                </div>

		<img class="reg12bg" src="12reg.png">

		
		<div id="useless">
			                <div class="clear_header">! iPad Air</div>

                			<div class="clear_div">
               				<a type="button" class="clear_link"      style="font-weight: 200;"    href="test_view_products.php?id=31">Learn More ></a>
                			<a type="button" class="clear_link"  style="font-weight: 200;"      href="add_to_cart.php?id=31">Add to bag ></a>
                			</div>
			
			<img class="ipadImg" src="ipadV2.png"  alt="iPad Air">
		</div>


		<div>
			<h1 style='color:<?php printf( "#%06X\n", mt_rand( 0, 0xffffff )); ?>'>THIS TEXT IS A RANDOM COLOR</h1>
		</div>
				
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


