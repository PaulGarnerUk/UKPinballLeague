<?php
	session_start();

	if (isset($_SESSION["userguid"]))
	{
		// probably need to lookup the user by guid, then determine whether this is an admin, league co-ordinator, or just regular user
		exit;
	}

    // Define variables and initialize
    $email = $password = $confirm_password = "";
    $email_err = $password_err = $confirm_password_err = $login_err = "";
    $emailRegex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";

    // Proces data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Validate email
        if (!preg_match($emailRegex, trim($_POST["email"])))
        {
            $email_err = "Please enter a valid email address.";
        } 
        else
        {
            // Prepare a select statement
            $sql = "SELECT id FROM users WHERE email = ?";
        
            if ($stmt = mysqli_prepare($link, $sql))
            {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_username);
            
                // Set parameters
                $param_username = trim($_POST["email"]);
            
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt))
                {
                    /* store result */
                    mysqli_stmt_store_result($stmt);
                
                    if(mysqli_stmt_num_rows($stmt) == 1)
                    {
                        $username_err = "This username is already taken.";
                    } 
                    else
                    {
                        $username = trim($_POST["username"]);
                    }
                } 
                else
                {
                    echo "Oops! Something went wrong. Please try again later.";
                }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if (empty(trim($_POST["password"])))
    {
        $password_err = "Please enter a password.";     
    } 
    elseif(strlen(trim($_POST["password"])) < 6)
    {
        $password_err = "Password must have atleast 6 characters.";
    } 
    else
    {
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"])))
    {
        $confirm_password_err = "Please confirm password.";     
    } 
    else
    {
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password))
        {
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err))
    {
        // generate a guid.

        // Insert user into db
        // INSERT INTO Users (email, password, guid, ) // todays date/time

        // Send email with link back to site confirmemail?id=guid 
        // (once we hit that confirmation page, and validate the guid (within a certain time window) then we update the user to set Active = 1
        // (Does the confirmation page ask them to login before making that update? Hmm)

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                // Redirect to login page
                header("location: login.php");
            } 
            else
            {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}

?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>UK Pinball League - Register</title>

<!-- Header and menu -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<?php include("includes/header.inc"); ?>


</head>
<body>

<div class="panel">
<div class="container">

    <h1 class="ms-3">Sign Up</h1>

    <p class="ms-3">Please fill in your details below to create an account.</p>

    <?php 
    if(!empty($login_err)){
        echo '<div class="alert alert-danger">' . $login_err . '</div>';
    }        
    ?>
	
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="d-grid gap-3 ms-3 me-3">
        <div class="form-group">
            <label>Email Address</label>
            <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
            <span class="invalid-feedback"><?php echo $email_err; ?></span>
        </div>    
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-secondary ml-2" value="Reset">
        </div>
        <p class="ms-0">Already have an account? <a href="login.php">Login here</a>.</p>
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