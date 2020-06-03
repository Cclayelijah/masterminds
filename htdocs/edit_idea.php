<?php
# edit_idea.php
#This page edits an idea
#Created by Elijah Cannon

session_start(); // Start the session.

$page_title = "Edit Idea";
include('includes/header.php');
require ('../mysqli_connect.php'); // Connect to the db.

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
    $id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<p class="error">This page has been accessed in error.</p>';
	include ('includes/footer.html');
	exit();
}

// check if idea's owner is the one logged in.
$q = "SELECT user_id FROM ideas WHERE idea_id = $id LIMIT 1";
$r = @mysqli_query ($dbc, $q);
$row = @mysqli_fetch_array ($r, MYSQLI_ASSOC);

if ($row['user_id'] != $_SESSION['user_id']) { // idea's owner does not match the logged in user
    echo '<h1>Error</h1>';
    echo '<p class="error">You do not have permission to edit this idea.</p>';
        // Include the footer and quit the script:
    mysqli_close($dbc);
    include ('includes/footer.html');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

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
    if ($_FILES['upload']['error'] == 4){
        $q = "SELECT image_path FROM ideas WHERE idea_id = $id";
        $r = @mysqli_query ($dbc, $q);
        $row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
        $p = $row['image_path'];
    } elseif (isset($_FILES['upload'])) {
        // Validate the type. Should be JPEG or PNG.
        $allowed = array ('image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png');
        if (in_array($_FILES['upload']['type'], $allowed)) {

            // Define path and overwrite if exists
            $p = "../uploads/{$_FILES['upload']['name']}";

            // move file over
            if (move_uploaded_file ($_FILES['upload']['tmp_name'], $p)) {
            } // End of move... IF.

        } else { // Invalid type.
            echo '<p class="error">The uploaded file needs to be a JPEG or PNG image.</p>';
        }
    }

    // Check for an error:
    if ($_FILES['upload']['error'] > 0 && $_FILES['upload']['error'] != 4) {
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
                print 'No file was uploaded.'; //doesn't matter lol
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

        // Make the query:
        $q = "UPDATE ideas SET name = '$n', description = '$d', image_path = '$p' WHERE idea_id = $id";
        $r = @mysqli_query ($dbc, $q);
        if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

            // Print a message:
            echo '<p>The idea has been edited.</p>';

        } else { // If no changes were made
            echo '<p>No changes were made.</p>'; // Public message.
        }

    } else { // Report the errors.

        echo '<h1>Error!</h1>
        <p class="error">The following error(s) occurred:<br />';
        foreach ($errors as $msg) { // Print each error.
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p><p><br /></p>';

        mysqli_close($dbc); // Close the database connection.
        // Include the footer and quit the script:
        include ('includes/footer.html');
        exit();

    } // End of errors IF.

    // Delete the file if it still exists:
    if (file_exists ($_FILES['upload']['tmp_name']) && is_file($_FILES['upload']['tmp_name']) ) {
        unlink ($_FILES['upload']['tmp_name']);
    }
} // end of submission

// Always show the form
// Retrieve the user's information:
$q = "SELECT name, description, image_path FROM ideas WHERE idea_id = $id";
$r = @mysqli_query ($dbc, $q);
if (!$r){
    echo '<h1>System Error</h1>';
    echo '<p class="error">An error has occured. Sorry for the inconvenience.</p>';
    // Debugging message:
    echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
    // Include the footer and quit the script:
    mysqli_close($dbc);
    include ('includes/footer.html');
    exit();
}

if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form.

    // Get the user's information:
    $row = mysqli_fetch_array ($r, MYSQLI_ASSOC);

    //Variables and stuff
    $name = $row['name'];
    $description = $row['description'];

    echo '<h1>Edit Idea</h1>
    <form enctype="multipart/form-data" action="edit_idea.php" method="post">
    <p>Name: <input type="text" name="iname" size="30" maxlength="100" value="'; echo (isset($_POST['iname']) ? $_POST['iname'] : $name) . '"'; echo '/></p>
    Description:<textarea name="description" rows="15" cols="100">'; echo (isset($_POST['description']) ? $_POST['description'] : $description); echo '</textarea>
    ';
    if (isset($row['image_path']) && $row['image_path'] != ""){
        list($width, $height, $type, $attr) = getimagesize($row['image_path']);
        $image_name = substr ($row['image_path'], 11);
        echo '<p>Image:</p>
        <figure id="fig">
            <img src="show_image.php?image=' . $image_name . '" alt="Photograph of a ' . $name . '" width="' . $width . '" height="' . $height . '">
        </figure>';
    }

    echo '<input type="hidden" name="MAX_FILE_SIZE" value="524288" />
    <fieldset><legend>Select a JPEG or PNG image of 512KB or smaller to be uploaded (optional):</legend>
    <p><b>File:</b> <input type="file" name="upload"/></p>
    </fieldset>
    <input type="hidden" name="id" value="' . $id . '" />
    <div align="center"><input type="submit" name="submit" value="Submit" /></div>
    </form>';

} else { // Not a valid user ID.
    echo '<p class="error">This page has been accessed in error. :(</p>';
}

?>
