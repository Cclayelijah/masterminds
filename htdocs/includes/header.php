<html lang="en">
<head>
<title><?php echo "$page_title"?></title>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="icon" type="image/x-icon" href="images/lightBulbIcon.png">
</head>
<body>
<div id="wrapper">
	<header>
		<h1>M A S T E R M I N D S</h1>
	</header>
	<div id="contain">
	<nav>
		<ul>
			<li><a href="index.php">Ideas</a></li>
			<!--if not logged in or is logged in as a mastermind: (submit idea is viewable)-->
			<?php if (isset($_SESSION['user_id'])) {
				echo '<li><a href="submit_idea.php">Submit Idea</a></li>';
			} ?>
			<li><a href="profile.php">My Profile</a></li>
			<li><a href="about.php">About</a></li>
			<li><a href="feedback.php">Give Feedback</a></li>
		</ul>
		<?php if (isset($_SESSION['user_id'])) {
					echo '<p>Hello, ' . $_SESSION['first_name'] . '!';
					echo '<p><b><a id="login" href="logout.php">Logout</a></b></p>';
				} else {
					echo '<p>Good day!</p>';
					echo '<p><b><a href="login_page.php">Login</a></b></p>';
				} ?>
	</nav>
	<div id="content">
