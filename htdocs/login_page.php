<?php
# login_page.php
#This page prints any errors associated with logging in
#and it creates the entire login page, including the form.

$page_title = 'Login';
include ('includes/header.php');

if (isset($errors) && !empty($errors)) {
	echo '<h1>Error!</h1>
	<p class="error">The following error(s) occurred:<br />';
	foreach ($errors as $msg) {
		echo " - $msg<br />\n";
	}
	echo '</p><p>Please try again.</p>';
}

?>
<h1>Login</h1>
<form action="login.php" method="post">
	<p>Email Address: <input type="text" name="email" size="20" maxlength="60"
	<?php if (isset($_POST['email'])) { echo 'value="' . $_POST['email'] . '"';} ?>/></p>
	<p>Password: <input type="password" name="pass" size="20" maxlength="20"
	<?php if (isset($_POST['pass'])) { echo 'value="' . $_POST['pass'] . '"';} ?>/></p>
	<p><input type="submit" name="submit" value="Login" /></p>
</form>
<p>Don't have an account? <a href="sign_up.php">Sign Up</a></p>

<?php include ('includes/footer.html'); ?>
