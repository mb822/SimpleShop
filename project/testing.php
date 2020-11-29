<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>
<!-- CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
      integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

<!-- jQuery and JS bundle w/ Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item"><a class="nav-link" href="<?php echo getURL("home.php"); ?>">Home</a></li>
        <?php if (!is_logged_in()): ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo getURL("login.php"); ?>">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo getURL("register.php"); ?>">Register</a></li>
        <?php endif; ?>
        <?php if (has_role("Admin")): ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo getURL("test/test_create_egg.php"); ?>">Create
                    Egg</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo getURL("test/test_list_egg.php"); ?>">View
                    Eggs</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo getURL("test/test_create_incubator.php"); ?>">Create
                    Incubator</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="<?php echo getURL("test/test_list_incubators.php"); ?>">View
                    Incubator</a>
            </li>
        <?php endif; ?>
        <?php if (is_logged_in()): ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo getURL("shop.php"); ?>">Shop</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo getURL("profile.php"); ?>">Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo getURL("logout.php"); ?>">Logout</a></li>
        <?php endif; ?>
    </ul>
    <span class="navbar-text">Balance: <?php echo getBalance(); ?></span>
</nav>
view rawnav.php hosted with ❤ by GitHub
profile.php
My next step was to work on my profile page, no particular reason for the order it's just what I jumped to next.

I just dropped down to my <form> and started following the form examples on the Bootstrap site.

So I wrap my label and input fields in a <div> and apply the form-group class.

Then on my input fields, I apply the form-control class.

Then instant better-looking forms!

It's really that easy to get a decent design with very little effort.

<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//Note: we have this up here, so our update happens before our get/fetch
//that way we'll fetch the updated data and have it correctly reflect on the form below
//As an exercise swap these two and see how things change
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

$db = getDB();
//save data if we submitted the form
if (isset($_POST["saved"])) {
    $isValid = true;
    //check if our email changed
    $newEmail = get_email();
    if (get_email() != $_POST["email"]) {
        //TODO we'll need to check if the email is available
        $email = $_POST["email"];
        $stmt = $db->prepare("SELECT COUNT(1) as InUse from Users where email = :email");
        $stmt->execute([":email" => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $inUse = 1;//default it to a failure scenario
        if ($result && isset($result["InUse"])) {
            try {
                $inUse = intval($result["InUse"]);
            }
            catch (Exception $e) {

            }
        }
        if ($inUse > 0) {
            flash("Email already in use");
            //for now we can just stop the rest of the update
            $isValid = false;
        }
        else {
            $newEmail = $email;
        }
    }
    $newUsername = get_username();
    if (get_username() != $_POST["username"]) {
        $username = $_POST["username"];
        $stmt = $db->prepare("SELECT COUNT(1) as InUse from Users where username = :username");
        $stmt->execute([":username" => $username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $inUse = 1;//default it to a failure scenario
        if ($result && isset($result["InUse"])) {
            try {
                $inUse = intval($result["InUse"]);
            }
            catch (Exception $e) {

            }
        }
        if ($inUse > 0) {
            flash("Username already in use");
            //for now we can just stop the rest of the update
            $isValid = false;
        }
        else {
            $newUsername = $username;
        }
    }
    if ($isValid) {
        $stmt = $db->prepare("UPDATE Users set email = :email, username= :username where id = :id");
        $r = $stmt->execute([":email" => $newEmail, ":username" => $newUsername, ":id" => get_user_id()]);
        if ($r) {
            flash("Updated profile");
        }
        else {
            flash("Error updating profile");
        }
        //password is optional, so check if it's even set
        //if so, then check if it's a valid reset request
        if (!empty($_POST["password"]) && !empty($_POST["confirm"])) {
            if ($_POST["password"] == $_POST["confirm"]) {
                $password = $_POST["password"];
                $hash = password_hash($password, PASSWORD_BCRYPT);
                //this one we'll do separate
                $stmt = $db->prepare("UPDATE Users set password = :password where id = :id");
                $r = $stmt->execute([":id" => get_user_id(), ":password" => $hash]);
                if ($r) {
                    flash("Reset Password");
                }
                else {
                    flash("Error resetting password");
                }
            }
        }
//fetch/select fresh data in case anything changed
        $stmt = $db->prepare("SELECT email, username from Users WHERE id = :id LIMIT 1");
        $stmt->execute([":id" => get_user_id()]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $email = $result["email"];
            $username = $result["username"];
            //let's update our session too
            $_SESSION["user"]["email"] = $email;
            $_SESSION["user"]["username"] = $username;
        }
    }
    else {
        //else for $isValid, though don't need to put anything here since the specific failure will output the message
    }
}


?>

    <form method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input class="form-control" type="email" name="email" value="<?php safer_echo(get_email()); ?>"/>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input class="form-control" type="text" maxlength="60" name="username"
                   value="<?php safer_echo(get_username()); ?>"/>
        </div>
        <div class="form-group">
            <!-- DO NOT PRELOAD PASSWORD-->
            <label for="pw">Password</label>
            <input class="form-control" type="password" name="password"/>
        </div>
        <div class="form-group">
            <label for="cpw">Confirm Password</label>
            <input class="form-control" type="password" name="confirm"/>
        </div>
        <input class="form-control" type="submit" name="saved" value="Save Profile"/>
    </form>
<?php require(__DIR__ . "/partials/flash.php");
