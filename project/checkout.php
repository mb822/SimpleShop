<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
//    if (isset($_SESSION["directfromcart"])  &&  $_SESSION["directfromcart"] == "true"  ) {
       	// flash(var_dump($_SESSION["directfromcart"]));
//	 $_SESSION["directfromcart"] = "false";
	 //flash(var_dump($_SESSION["directfromcart"]));
//    }
//    else{
//        flash("Please checkout from this page.");
//        die(header("Location: testcart.php"));
//    }
?>






<?php
if(isset($_POST["saved"])){
	
	$total = 0.000001;
	foreach($_SESSION["currentcartitems"] as $item){
		$total += $item["sub"];
	}
	$total = $total*1.07;
	

	$payment_method = $_POST["payment_method"];
	$address = $_POST["address"].", ".$_POST["city"].", ".$_POST["state"]." ".$_POST["zipcode"];
        $user = get_user_id();
        $db = getDB();

        $stmt = $db->prepare("INSERT INTO Orders  (user_id, total_price, address, payment_method) VALUES(:user_id, :total_price, :address, :payment_method)");
        $r = $stmt->execute([
                ":user_id"=>$user,
                ":total_price"=>$total,
                ":address"=>$address,
                ":payment_method"=>$payment_method ]);


	$order_id = $db->lastInsertId();



	//remove purchased items from cart
        foreach($_SESSION["currentcartitems"] as $item){
                if($item["sub"] > 0){
			$product_id = $item["product_id"];
			
			$stmt = $db->prepare("DELETE  FROM Cart WHERE user_id = $user AND product_id = $product_id "  );
			$r = $stmt->execute();
		}
	}






	//update product stock
	//UPDATE Products set  checkout_img=:checkout_img
	        foreach($_SESSION["currentcartitems"] as $item){
                if($item["sub"] > 0){
			$product_id = $item['product_id'];
                        if($item["prod_quantity"] > $item["quantity"] ){
				$quantitytoremove = $item["quantity"];
                                $stmt = $db->prepare("   UPDATE Products set quantity=(quantity-$quantitytoremove) WHERE id=$product_id        ");
                                $r = $stmt->execute();
                        }
                        else{
				$quantitytoremove = $item['prod_quantity'];
                             	$stmt = $db->prepare("UPDATE Products set quantity=(quantity-$quantitytoremove) WHERE id=$product_id     ");
                                $r = $stmt->execute();
                        }
                }

        }
	


















	//inserting items into orderItems table
	foreach($_SESSION["currentcartitems"] as $item){
		if($item["sub"] > 0){
			if($item["prod_quantity"] > $item["quantity"] ){
				$stmt = $db->prepare("INSERT INTO OrderItems  (order_id, product_id, quantity, unit_price) VALUES(:order_id, :product_id, :quantity, :unit_price)");
        			$r = $stmt->execute([
                		":order_id"=>$order_id,
                		":product_id"=>$item["product_id"],
                		":quantity"=>$item["quantity"],
                		":unit_price"=>$item["price"] ]);
			}
			else{	
		        	$stmt = $db->prepare("INSERT INTO OrderItems  (order_id, product_id, quantity, unit_price) VALUES(:order_id, :product_id, :quantity, :unit_price)");
                                $r = $stmt->execute([
                                ":order_id"=>$order_id,
                                ":product_id"=>$item["product_id"],
                                ":quantity"=>$item["prod_quantity"],
                                ":unit_price"=>$item["price"] ]);		
			}
		}
	
	}



    $_SESSION["order_number"] = $order_id;
    flash("Thank you for your purchase in the amount of $".number_format($total, 2)  );
    die(header("Location: confirmation.php"));



}
?>


























<form method="POST">

        <label for= "pleasesignin">Checkout</label>
			

	<div style="margin-left:11%; font-size: 1.3em;font-weight: 400;margin-bottom: 20;color: #5c5c5c;">Shipping Information:</div>
	<div style="width: 700px;display: flex; margin-left:11%">
        	<input type="text"   style="margin: 0px 0px 0px 0px; width:350px"  name="name" placeholder="Full Name" required >
       <!-- 	<input type="text"  style="margin: 0px 0px 0px 0px; width:170px"  name="lastname" placeholder="Last Name"  >   -->
	</div>


        <input type="tel" name="phone" placeholder="Phone Number" />
        <input type="text" name="address"  placeholder="Address" required  />
        <input type="text" name="city"  placeholder="City"  required  />


<div style="width: 700px;display: flex; margin-left:11%">	

<select name="state" required  style="margin: 0px 10px 0px 0px; width:170px" required / >
	<option value="" selected  disabled >State:</option>
	<option value="AL">Alabama</option>
	<option value="AK">Alaska</option>
	<option value="AZ">Arizona</option>
	<option value="AR">Arkansas</option>
	<option value="CA">California</option>
	<option value="CO">Colorado</option>
	<option value="CT">Connecticut</option>
	<option value="DE">Delaware</option>
	<option value="DC">District of Columbia</option>
	<option value="FL">Florida</option>
	<option value="GA">Georgia</option>
	<option value="HI">Hawaii</option>
	<option value="ID">Idaho</option>
	<option value="IL">Illinois</option>
	<option value="IN">Indiana</option>
	<option value="IA">Iowa</option>
	<option value="KS">Kansas</option>
	<option value="KY">Kentucky</option>
	<option value="LA">Louisiana</option>
	<option value="ME">Maine</option>
	<option value="MD">Maryland</option>
	<option value="MA">Massachusetts</option>
	<option value="MI">Michigan</option>
	<option value="MN">Minnesota</option>
	<option value="MS">Mississippi</option>
	<option value="MO">Missouri</option>
	<option value="MT">Montana</option>
	<option value="NE">Nebraska</option>
	<option value="NV">Nevada</option>
	<option value="NH">New Hampshire</option>
	<option value="NJ">New Jersey</option>
	<option value="NM">New Mexico</option>
	<option value="NY">New York</option>
	<option value="NC">North Carolina</option>
	<option value="ND">North Dakota</option>
	<option value="OH">Ohio</option>
	<option value="OK">Oklahoma</option>
	<option value="OR">Oregon</option>
	<option value="PA">Pennsylvania</option>
	<option value="RI">Rhode Island</option>
	<option value="SC">South Carolina</option>
	<option value="SD">South Dakota</option>
	<option value="TN">Tennessee</option>
	<option value="TX">Texas</option>
	<option value="UT">Utah</option>
	<option value="VT">Vermont</option>
	<option value="VA">Virginia</option>
	<option value="WA">Washington</option>
	<option value="WV">West Virginia</option>
	<option value="WI">Wisconsin</option>
	<option value="WY">Wyoming</option>
</select>	


	<input type="number" name="zipcode" minlength="5"  style="margin: 0px 0px 0px 0px; width:170px"  placeholder="Zipcode" required />
</div>


<div style="margin-left:11%; font-size: 1.3em;font-weight: 400;margin-bottom: 20; margin-top:60px;color: #5c5c5c;">Payment Information:</div>


      	<select name="payment_method" style="height: 50px;margin: 0px 0px 11%px 0px;    padding: 15px 15px; width:350px;" required >
        	<option value="" selected  disabled >Payment Type:</option>
        	<option value="cash">Cash</option>
		<option value="visa">Visa</option>
		<option value="mastercard">Mastercard</option>
		<option value="paypal">Paypal</option>
		<option value="applepay">Apple Pay</option>
	</select>



        <input type="submit" name="saved" value="Confim and Pay"/>
    </form>

<?php require_once(__DIR__ . "/partials/flash.php");
