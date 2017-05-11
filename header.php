<?php
echo "
<header>
<span><a href='profile.php'>Home</a></span>
<span class='nav-right'><a href='logout.php'>Log out</a></span>
<span class='nav-right nav-info'>Logged in as <strong>" . $_SESSION['username'] . "</strong></span>
</header>
";
?>