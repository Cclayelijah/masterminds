<?php
#sign_up.php
# This script performs an INSERT query to add a record to the users table

$page_title = 'Sign Up';
include ('includes/header.php');

// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	require ('../mysqli_connect.php'); // Connect to the db.

    $errors = array(); // Initialize an error array.

	// Check for a first name:
	if (empty($_POST['first_name'])) {
		$errors[] = 'You forgot to enter your first name.';
	} else {
		$fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
	}

	// Check for a last name:
	if (empty($_POST['last_name'])) {
		$errors[] = 'You forgot to enter your last name.';
	} else {
		$ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
    }

	// Check for an email address:
    $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
	if (empty($_POST['email'])) {
		$errors[] = 'You forgot to enter your email address.';
	} else if (preg_match('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', $email)) {
        $e = $email;
	} else {
        $errors[] = 'Your email address is invalid.';
    }

	// Check for a password and match against the confirmed password:
	if (!empty($_POST['pass1'])) {
		if ($_POST['pass1'] != $_POST['pass2']) {
			$errors[] = 'Your passwords do not match.';
		} else {
			$p = mysqli_real_escape_string($dbc, trim($_POST['pass1']));
		}
	} else {
		$errors[] = 'You forgot to enter your password.';
	}

	if (empty($errors)) { // If everything's OK.

		// Register the user in the database...

		// Make the query:
		$q = "INSERT INTO users (first_name, last_name, email, pass, registration_date) VALUES ('$fn', '$ln', '$e', SHA1('$p'), NOW() )";
		$r = @mysqli_query ($dbc, $q); // Run the query.
		if ($r) { // If it ran OK.
			// Print a message:
            echo '<h1>Thank you for registering, ' . $fn . '!</h1>';
            echo "<p>Be the Mastermind behind the world's next greatest invention! Happy Brainstorming :)</p>";
            echo '<p>Remember to <a href="login.php">sign in</a> to your account.</p>';
		} else { // If it did not run OK.

			// Public message:
			echo '<h1>System Error</h1>
			<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';

			// Debugging message:
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';

		}

		mysqli_close($dbc); // Close the database connection.

		// Include the footer and quit the script:
		include ('includes/footer.html');
		exit();

	} else { // Report the errors.

		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';

	} // End of if (empty($errors)) IF.

	mysqli_close($dbc); // Close the database connection.

} // End of the main Submit conditional.
?>
<h1>Sign Up</h1>
<form action="sign_up.php" method="post">
	<p>First Name: <input type="text" name="first_name" size="15" maxlength="20" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" /></p>
	<p>Last Name: <input type="text" name="last_name" size="15" maxlength="40" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>" /></p>
	<p>Email Address: <input type="text" name="email" size="20" maxlength="60" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"  /> </p>
	<p>Password: <input type="password" name="pass1" size="10" maxlength="20" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>"  /></p>
	<p>Confirm Password: <input type="password" name="pass2" size="10" maxlength="20" value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>"  /></p>
	<p><input type="submit" name="submit" value="Sign Up" /></p>
</form>
<p><a href="login_page.php">Already have an account?</a></p>
<?php include ('includes/footer.html'); ?>
