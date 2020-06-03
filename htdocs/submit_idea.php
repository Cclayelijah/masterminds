<?php
#sign_up.php
# This script performs an INSERT query to add a record to the users table

session_start(); // Start the session.

$page_title = 'Submit Idea';
include ('includes/header.php');

// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	require ('../mysqli_connect.php'); // Connect to the db.

	$errors = array(); // Initialize an error array.

	// Check for an idea name:
	if (empty($_POST['iname'])) {
		$errors[] = 'You forgot to enter the name of your idea.';
	} else {
		$n = mysqli_real_escape_string($dbc, trim($_POST['iname']));
	}

	// Check for a description:
	if (empty($_POST['description'])) {
		$errors[] = 'You forgot to enter the description.';
	} else {
		$d = mysqli_real_escape_string($dbc, trim($_POST['description']));
	}

	// Check for an uploaded file
	if (isset($_FILES['upload'])) {
		// Validate the type. Should be JPEG or PNG.
		$allowed = array ('image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png');
		if (in_array($_FILES['upload']['type'], $allowed)) {

			// Define path and redefine if exists
			$p = "../uploads/{$_FILES['upload']['name']}";
			if (file_exists($p)){
				$file = new SplFileInfo($_FILES['upload']['name']);
				$ext = '.' . $file->getExtension();
				while (file_exists($p)) {
					$p = substr_replace($p, "", -strlen($ext));
					$p = $p . rand(0,9);
					$p = $p . $ext;
				}
			}

			// move file over
			if (move_uploaded_file ($_FILES['upload']['tmp_name'], $p)) {
			} // End of move... IF.

		} else { // Invalid type.
			echo '<p class="error">The uploaded file needs to be a JPEG or PNG image.</p>';
		}
	}

		// Check for an error:
	if ($_FILES['upload']['error'] > 0) {
		echo '<p class="error">The file could not be uploaded because: <strong>';

		// Print a message based upon the error.
		switch ($_FILES['upload']['error']) {
			case 1:
				print 'The file exceeds the upload_max_filesize setting in php.ini.';
				break;
			case 2:
				print 'The file exceeds the MAX_FILE_SIZE setting in the HTML form.';
				break;
			case 3:
				print 'The file was only partially uploaded.';
				break;
			case 4:
				print 'No file was uploaded.';
				break;
			case 6:
				print 'No temporary folder was available.';
				break;
			case 7:
				print 'Unable to write to the disk.';
				break;
			case 8:
				print 'File upload stopped.';
				break;
			default:
				print 'A system error occurred.';
				break;
		} // End of switch.

		print '</strong></p>';

	} else if (empty($errors)) { // If everything's OK.

		$u = $_SESSION['user_id'];
		$fn = $_SESSION['first_name'];

		// Register the user in the database...

		// Make the query:
		$q = "INSERT INTO ideas (user_id, name, description, image_path, date_entered) VALUES ('$u', '$n', '$d', '$p', NOW() )";
		$r = @mysqli_query ($dbc, $q); // Run the query.
		if ($r) { // If it ran OK.

			// Print a message:
			echo '<h1>Thank you for submitting an idea!</h1>';
			$q2 = "SELECT idea_id FROM ideas WHERE user_id = $u ORDER BY date_entered DESC LIMIT 1;";
			$r2 = @mysqli_query ($dbc, $q2);
			if ($r2) {
				$result = mysqli_fetch_array($r2);
				echo '<p><a href="index.php?id=' . $result[0] . '">View your idea</a></p>';
			}

		} else { // If it did not run OK.

			// Public message:
			echo '<h1>System Error</h1>
			<p class="error">Your idea could not be submitted due to a system error. We apologize for any inconvenience.</p>';

			// Debugging message:
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';

		} // End of if ($r) IF.

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

	} // End of errors IF.

	// Delete the file if it still exists:
	if (file_exists ($_FILES['upload']['tmp_name']) && is_file($_FILES['upload']['tmp_name']) ) {
		unlink ($_FILES['upload']['tmp_name']);
	}

	mysqli_close($dbc); // Close the database connection.

} // End of the main Submit conditional.

if (!isset($_SESSION['user_id'])) {
	echo '<p>You must <a href="login_page.php">sign in</a> before you can submit an idea.</p>';
} else {
	echo '<h1>Submit an Idea</h1>
	<form enctype="multipart/form-data" action="submit_idea.php" method="post">
		<p>Name: <input type="text" name="iname" size="30" maxlength="100"'; if (isset($_POST['iname'])) echo 'value="' . $_POST['iname'] . '"'; echo '/></p>
		Description:<textarea name="description" rows="15" cols="100">'; if (isset($_POST['description'])) {echo $_POST['description'];} echo '</textarea>
		<input type="hidden" name="MAX_FILE_SIZE" value="524288" />
		<fieldset><legend>Select a JPEG or PNG image of 512KB or smaller to be uploaded:</legend>
		<p><b>File:</b> <input type="file" name="upload"'; if (isset($_POST['upload'])) echo 'value="' . $_POST['upload'] . '"'; echo '/></p>
		</fieldset>
		<div align="center"><input type="submit" name="submit" value="Submit" /></div>
	</form>';
}

include ('includes/footer.html'); ?>
