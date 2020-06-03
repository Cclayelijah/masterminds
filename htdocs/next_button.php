<?php # functions.php
// This script runs when the "right_arrow_button" is clicked.

include ('includes/login_functions.inc.php');

if (!isset($GET['id']) || !isset($GET['page'])){
    echo '<p class="error">An error has occured.</p>';
} else {
    $id = $GET['id'];
    if (!isset($_COOKIE['history']))
    setcookie('history', $id . ', ');
    else {
        setcookie('history', $_COOKIE['history'] . $id . ', ');
    }
}

redirect_user();

?>  