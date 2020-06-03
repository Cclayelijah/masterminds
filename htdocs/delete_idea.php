<?php
# delete_idea.php
#This page deletes an idea
#Created by Elijah Cannon

session_start(); // Start the session.

$page_title = "Delete Idea";
include('includes/header.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} elseif (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = $_POST['id'];
} else {
    echo '<h1>Error</h1>
    <p class="error">This page has been accessed in error.</p>';
    include ('includes/footer.html');
    exit;
}

require ('../mysqli_connect.php'); // Connect to the db.

// check if idea's owner is the one logged in.
$q = "SELECT user_id FROM ideas WHERE idea_id = $id";
$r = @mysqli_query ($dbc, $q);
$row = @mysqli_fetch_array ($r, MYSQLI_ASSOC);

if ($row['user_id'] != $_SESSION['user_id']) { // idea's owner does not match the logged in user
    echo '<h1>Error</h1>';
    echo '<p class="error">You do not have permission to delete this idea.</p>';
        // Include the footer and quit the script:
    mysqli_close($dbc);
    include ('includes/footer.html');
    exit();
} else { // has permission


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if ($_POST['sure'] == 'Yes') { // Delete the record.
            // Make the query:
            $q = "DELETE FROM ideas WHERE idea_id = $id LIMIT 1";
            $r = @mysqli_query ($dbc, $q);
            if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

                // Print a message:
                echo '<p>The idea has been deleted.</p>';

            } else { // If the query did not run OK.
                echo '<p class="error">The idea could not be deleted due to a system error.</p>'; // Public message.
                echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
            }

        } else { // No confirmation of deletion.
            echo '<p>The idea has NOT been deleted.</p>';
        }

    }  else { // else show the form
        // Retrieve the user's information:
        $q = "SELECT name, description FROM ideas WHERE idea_id = $id";
        $r = @mysqli_query ($dbc, $q);

        if (mysqli_num_rows($r) == 1) { // Valid idea ID, show the form.

            // Get the user's information:
            $row = mysqli_fetch_array ($r, MYSQLI_ASSOC);

            // Display the record being deleted:
            echo '<h3>Name: ' . $row['name'] . '</h3><h3>Description: ' . $row['description'] . '</h3>
            <h2>Are you sure you want to delete this idea?</h2>';

            // Create the form:
            echo '<form action="delete_idea.php" method="post">
            <input type="radio" name="sure" value="Yes" /> Yes
            <input type="radio" name="sure" value="No" checked="checked" /> No
            <input type="submit" name="submit" value="Submit" />
            <input type="hidden" name="id" value="' . $id . '" />
            </form>';

        } else { // Not a valid user ID.
            echo '<p class="error">This page has been accessed in error.</p>';
        }
    }
}
include ('includes/footer.html');
?>
