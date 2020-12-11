<head>
	<meta content="width=device-width, initial-scale=1, minimum-scale=1, user-scalable=no" name="viewport">
</head>

<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>

<?php 
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {$ip = $_SERVER['HTTP_CLIENT_IP'];}
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];}
    else {$ip = $_SERVER['REMOTE_ADDR'];}

    $db = getDB();    
	
    $stmt = $db->prepare("INSERT INTO Viewers (ip, user_id) VALUES(:ip, :user_id)");
    $r = $stmt->execute([ ":user_id"=>get_user_id(), ":ip"=>$ip]);
    	if($r){flash("");}
	else{
            $e = $stmt->errorInfo();
            flash("Error: " . var_export($e, true));
        }
?>


<!-- CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

<!-- jQuery and JS bundle w/ Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<!--  <body style="background-color: #000000;"> -->

<link rel="stylesheet" href="static/css/styles.css">

<body style="background-color: #ffffff;">


<div class="sticky-top">
<nav class="navbar      navbar-dark " align="center" >
 <!-- <a class="navbar-brand" align="center"  href="#">Peremanent Nav elem</a> -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
   <!-- <span class="navbar-toggler-icon"></span> -->
	<svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-list" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2.5 11.5A.5.5 0 0 1 3 11h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 3h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/></svg>
  </button>


<a class="navbar-brand" align="center"  href="home.php">!<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.2em" height="1.2em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 512 512"><path d="M349.13 136.86c-40.32 0-57.36 19.24-85.44 19.24c-28.79 0-50.75-19.1-85.69-19.1c-34.2 0-70.67 20.88-93.83 56.45c-32.52 50.16-27 144.63 25.67 225.11c18.84 28.81 44 61.12 77 61.47h.6c28.68 0 37.2-18.78 76.67-19h.6c38.88 0 46.68 18.89 75.24 18.89h.6c33-.35 59.51-36.15 78.35-64.85c13.56-20.64 18.6-31 29-54.35c-76.19-28.92-88.43-136.93-13.08-178.34c-23-28.8-55.32-45.48-85.79-45.48z" fill="white"/><path d="M340.25 32c-24 1.63-52 16.91-68.4 36.86c-14.88 18.08-27.12 44.9-22.32 70.91h1.92c25.56 0 51.72-15.39 67-35.11c14.72-18.77 25.88-45.37 21.8-72.66z" fill="white"/><rect x="0" y="0" width="512" height="512" fill="rgba(0, 0, 0, 0)" /></svg></a>
<a class="navbar-brand" align="center"  href="testcart.php"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-bag" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 1a2.5 2.5 0 0 0-2.5 2.5V4h5v-.5A2.5 2.5 0 0 0 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V5H2z"/></svg></a>



  <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">

	<?php if (!is_logged_in()): ?>
            <li><a href="login.php" class="nav-link" >Login</a></li>
            <li><a href="register.php" class="nav-link" >Register</a></li>
	<?php endif; ?>

	<?php if (is_logged_in()): ?>
	    <li><a href="orders.php" class="nav-link" >Orders</a></li>
            <li><a href="profile.php" class="nav-link" >Profile</a></li>
            <li><a href="logout.php" class="nav-link" >Logout</a></li>
	<?php endif; ?>

	<a class="nav-link" href="test_list_products.php">Search</a>
	
	<?php if (has_role("Admin")): ?>
	    <a class="nav-link" href="test_create_products.php">ADMIN: Create Product&nbsp;&nbsp;</a>
            <a class="nav-link" href="test_list_products.php">ADMIN: Search Products</a>
            <a class="nav-link" href="test_create_cart.php">ADMIN: Create Cart&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
            <a class="nav-link" href="test_list_carts.php">ADMIN: Search Cart&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
	<?php endif; ?>
    </ul>
  </div>


</nav>
</div>
