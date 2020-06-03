<?php
# profile.php
#This page shows your ideas
#Created by Elijah Cannon

//profile query:
//  $q = "SELECT i.idea_id, i.user_id, u.first_name, i.name, i.description, i.image_path, i.likes, i.pursuer, i.date_entered
//  FROM ideas AS i INNER JOIN users AS u ON i.user_id = u.user_id WHERE user_id = " . $_GET['user_id'] . " ORDER BY date_entered DESC LIMIT $index, 1";

session_start(); // Start the session.

$page_title = "My Profile";
include('includes/header.php');

if (!isset($_SESSION['user_id'])) {
    echo '<p>You must <a href="login.php">sign in</a> before you can view this page.';
}
else { // you can only view this page if you are signed in.

    require ('../mysqli_connect.php'); // Connect to the db.
    include ('includes/functions.php');

    // $user_id = (isset($_GET['u']) ? $_GET['u'] : $_SESSION['user_id']);

    // Define the query:
    $q = "SELECT first_name, last_name, registration_date FROM users WHERE user_id = " . $_SESSION['user_id'];
    //Run the Query
    $r = @mysqli_query ($dbc, $q);
    if ($r) { // successful
        $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
    } else {
        echo '<h1>System Error</h1>';
        echo '<p class="error">An error has occured. Sorry for the inconvenience.</p>';
        // Debugging message:
        echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
        // Include the footer and quit the script:
        mysqli_close($dbc);
        include ('includes/footer.html');
        exit();
    }
    ?>

    <!-- page specific content -->
    <h1>Account</h1>
    <p>Name: <?php echo $row['first_name'] . ' ' . $row['last_name']; ?></p>
    <p>Registration Date: <?php echo $row['registration_date']; ?></p>

    <?php

    // Edit and Delete account
    echo '<p><a href="edit_account.php?id=' . $_SESSION['user_id'] . '">Edit</a> / <a href="delete_account.php?id=' . $_SESSION['user_id'] . '">Delete</a></p>';

    // count number of user's ideas
    $q = "SELECT idea_id FROM ideas WHERE user_id = " . $_SESSION['user_id'];
    $r = @mysqli_query ($dbc, $q);
    $row = mysqli_fetch_row($r);
    $idea_count = $row[0];

    echo '<h1>Ideas</h1>';
    if ($idea_count == 0) { // hasn't submitted any ideas.
        echo '<p>You don\'t have any ideas.</p>';
    } else {

        // Define the query:
        $q = "SELECT i.idea_id, i.user_id, u.first_name, u.last_name, u.registration_date, i.name, i.description, i.image_path, i.likes, i.pursuer, i.date_entered
        FROM ideas AS i INNER JOIN users AS u ON i.user_id = u.user_id WHERE i.user_id = " . $_SESSION['user_id'] . " ORDER BY date_entered DESC";
        //Run the Query
        $r = @mysqli_query ($dbc, $q);

        // Table header:
        echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">
        <tr>
            <td align="left"><b></b></td>
            <td align="left"><b></b></td>
            <td align="left" style="color: white;"><b>Name</b></td>
            <td align="left" style="color: white;"><b>Description</b></td>
            <td align="left" style="color: white;"><b>Date Submitted</b></td>
        </tr>
        ';

        // Fetch and print all the records....
        $bg = '#eeeeee';
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            $bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
                echo '<tr bgcolor="' . $bg . '">
                <td align="left"><a href="edit_idea.php?id=' . $row['idea_id'] . '">Edit</a></td>
                <td align="left"><a href="delete_idea.php?id=' . $row['idea_id'] . '">Delete</a></td>
                <td align="left"><a href="user_ideas.php?id=' . $row['idea_id'] . '">' . $row['name'] . '</td>
                <td align="left">' . $row['description'] . '</td>
                <td align="left">' . $row['date_entered'] . '</td>
            </tr>
            ';
        } // End of WHILE loop.

        echo '</table>';

    } // end of count ideas IF

    mysqli_free_result ($r);
    mysqli_close($dbc);

} // end of sign in check

include('includes/footer.html');
?>
