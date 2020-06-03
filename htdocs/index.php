<?php
#index.php
#This page shows the idea feed
#Created by Elijah Cannon

session_start(); // Start the session.

$page_title = "Masterminds";
include('includes/header.php');

require ('../mysqli_connect.php'); // Connect to the db.
include ('includes/functions.php');

// Determine how many pages there are...
if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already been determined.
    $pages = $_GET['p'];
} else { // Need to determine.
    // Count the number of records:
    $q = "SELECT COUNT(idea_id) FROM ideas";
    $r = @mysqli_query ($dbc, $q);
    $row = @mysqli_fetch_array ($r, MYSQLI_NUM);
    if (isset($_GET['id']) && is_numeric($_GET['id'])) // if only one specific idea is being viewed
    $pages = 1;
    else
    $pages = $row[0];
} // End of p IF.

// Determine where in the database to start returning results...
if (isset($_GET['s']) && is_numeric($_GET['s'])) {
	$start = $_GET['s'];
} else {
	$start = 0;
}

// Define the query:
    $q = "SELECT i.idea_id, i.user_id, u.first_name, i.name, i.description, i.image_path, i.likes, i.pursuer, i.date_entered
    FROM ideas AS i INNER JOIN users AS u ON i.user_id = u.user_id ORDER BY date_entered DESC LIMIT $start, 1";

//Run the Query
$r = @mysqli_query ($dbc, $q);
if ($r) { // successful
    $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
} else {
    echo '<h1>System Error</h1>';
    echo '<p class="error">An error has occured. Sorry for the inconvenience.</p>';
        // Include the footer and quit the script:
    mysqli_close($dbc);
    include ('includes/footer.html');
    exit();
}

//Variables and stuff
//path = ../uploads/imagename.ext
list($width, $height, $type, $attr) = getimagesize($row['image_path']);
$image_name = substr ($row['image_path'], 11);

?>
<!-- page specific content -->
<h3>Mastermind: <a href="user.php?id=<?php echo $row['user_id']; ?>"><?php echo $row['first_name']; ?></a></h3>
<div class="parent">
<div id="panel">
    <div id="left_panel">
        <div id="left_arrow_button" class="button">
        <?php
        if ($pages > 1) {
            $current_page = $start + 1;
            if ($current_page != 1) { //if not the first page, show the previous button
                echo '<a href="index.php?p=' . $pages . '&s=' . ($start - 1) . '">' . '<img src="images/leftArrow.png" alt="Arrow Widget to view last idea" width="599" height="456"></a>';
            }
        }
        ?>
        </div> <!--left_arrow_button-->
    </div> <!--left_panel-->

        <figure id="fig">
            <img src="show_image.php?image=<?php echo $image_name; ?>" alt="Photograph of a <?php echo $row['name']; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>">
        </figure>

    <div id="right_panel">
        <div id="widgets" class="idea_section">
            <div id="like_button" class="button">
                <a href="like.php?id=<?php echo $row['idea_id']; ?>"><img src="images/heart.png" alt="Heart Widget" width="1024" height="1024">
                <div id="likes">45</div></a>
            </div> <!--like_button-->
            <div id="comments_button" class="button">
                <a href="comments.php?id=<?php echo $row['idea_id']; ?>"><img src="images/comment.png" alt="Comments Widget" width="626" height="626"></a>
            </div> <!--comments_button-->
        </div><!--widgets-->
        <div id="right_arrow_button" class="button">
            <?php
            if ($pages > 1) {
                $current_page = $start + 1;
                // If it's not the last page, make a Next button:
                if ($current_page < $pages) {
                    echo '<a href="index.php?p=' . $pages . '&s=' . ($start + 1) . '">' . '<img src="images/rightArrow.png" alt="Arrow Widget to view next idea" width="599" height="456"></a>';
                }
            }
            ?>
        </div> <!--right_arrow_button-->
    </div> <!--right_panel-->
</div> <!--panel-->
</div> <!--parent-->

<div class="idea_section">
<h3 style="margin: 10px;"><?php echo $row['name']; ?></h3>
<p><?php echo $row['description']; ?></p>
</div>

<?php
include('includes/footer.html');
?>
