// https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
// https://getbootstrap.com/docs/5.1/forms/form-control/

<?php
	session_start();

	if (isset($_SESSION["userguid"]))
	{
		// probably need to lookup the user by guid, then determine whether this is an admin, league co-ordinator, or just regular user
		exit;
	}

    // Define variables and initialize with empty values
    $username = $password = "";
    $username_err = $password_err = $login_err = "";

?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>UK Pinball League - Log In</title>

<!-- Header and menu -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<?php include("includes/header.inc"); ?>


</head>
<body>

<div class="panel">
<div class="container">

    <h1 class="ms-3">Log In</h1>

    <p class="ms-3">Please fill in your credentials below to login.</p>

    <?php 
    if(!empty($login_err)){
        echo '<div class="alert alert-danger">' . $login_err . '</div>';
    }        
    ?>
	
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="d-grid gap-3 ms-3 me-3">
        <div class="form-group">
            <label>Username / Email</label>
            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>    
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
        <p class="ms-0">Don't have an account? <a href="register.php">Sign up now</a>.</p>
    </form>
    <p></p>
    <p class="ms-3">Logging in offers a number of advanced features (<i>coming soon</i>):<br>
    - One click registration to events.<br>
    - Access address details of registered event (two weeks prior to event).<br>
    - Optional email notifications of new events in your area, cancellations etc.<br><br>
    </p>

</div>
</div>

<!-- Header and menu -->
<?php include("includes/footer.inc"); ?>

</body>
</html>