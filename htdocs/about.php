<?php
# about.php
#This page shows information on what this website is about
#Created by Elijah Cannon

session_start(); // Start the session.

$nest = 0;
$page_title = "About";
include('includes/header.php');
?>

<h1 style="text-align: center">About Masterminds</h1>
<h3 style="text-align: left">Why</h3>
<p style="text-align: left">I, Elijah Cannon created this website for my final project in my PHP and MySQL course in
     Septermber 2018.  It was all thought up by, designed, and created by me. I knew this was a project I wanted to create for a long time, and took this opportunity
     to jump in and make it happen. Even after ending this course I have worked on and improved
     it to give it life. It has become a side project.
</p><br>
<h3 style="text-align: left">What</h3>
<p style="text-align: left">
    Masterminds is a website that connects people with ideas and inventors. A mastermind can
    post ideas, and inventors can pursue them. Once being pursued, the inventor will stay
    connected with the mastermind in terms of how it's coming along. Once invented, the mastermind
    will be recognized as, well, the mastermind of course!
</p><br>
<h3 style="text-align: left">Features</h3>
<p style="text-align: left">
    Masterminds takes the form of a social media platform. You can view popular ideas, share
    ideas, like ideas and even comment on ideas. The sense of community and conversation
    will make some ideas seem more realistic.
</p><br>
<h3>Enjoy my creation!</h3>

<?php
include('includes/footer.html');
?>
