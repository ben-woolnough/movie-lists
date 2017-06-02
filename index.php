<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Movie Lists</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

<?php
// if user is logged in, redirects to profile.php
if (isset($_SESSION['logged_in'])) {
    if ($_SESSION['logged_in'] == true) {
        header("location: profile.php");
    }
}
// Account registration message
if (isset($_GET['registered'])) {
    if ($_GET['registered'] == 'true') {
        echo '<h2 class="notify">Account created!</h2>';
    } elseif ($_GET['registered'] == 'false') {
      echo '<h2 class="notify">Account not created.</h2>';
    }
}
// Invalid username or password
if (isset($_GET['invalid'])) {
    echo '<h2 class="notify">Invalid username or password.</h2>';
}
// Wrong username or password
if (isset($_GET['login_failed'])) {
    echo '<h2 class="notify">Wrong username or password.</h2>';
}
// Missing data
if (isset($_GET['missing_data'])) {
    echo '<h2 class="notify">Enter all fields.</h2>';
}
// Logout message
if (isset($_GET['logout'])) {
    echo '<h2 class="notify">Logged out.</h2>';
}

?>

<div class="login-container">

  <div id="login">
    <h1>Log in</h1>
    <form action="login.php" method="post">
      <input class="input-field" type="text" name="username" placeholder="Username"><br><br>
      <input class="input-field" type="password" name="password" placeholder="Password"><br><br>
      <input class="button" type="submit" name="submit">
    </form>
  </div> <!-- login -->

  <div id=register>
    <h1>Create account</h1>
    <form action="register.php" method="post">
      <input class="input-field" type="text" name="username" placeholder="Username"><br><br>
      <input class="input-field" type="password" name="password" placeholder="Password"><br><br>
      <input class="button" type="submit" name="submit">
    </form>
  </div> <!-- register -->

</div> <!-- login-container -->


</body>
</html>