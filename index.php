<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
  <title>Movie Lists</title>
  <link rel="stylesheet" type="text/css" href="style.css">
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
        echo '<h2>Account created!</h2>';
    } elseif ($_GET['registered'] == 'false') {
      echo '<h2>Account not created.</h2>';
    }
}
// Invalid username or password
if (isset($_GET['invalid'])) {
    echo '<h2>Invalid username or password.</h2>';
}
// Wrong password
if (isset($_GET['wrong_password'])) {
    echo '<h2>Wrong password.</h2>';
}
// Missing data
if (isset($_GET['missing_data'])) {
    echo '<h2>Enter all fields.</h2>';
}
// Logout message
if (isset($_GET['logout'])) {
    echo '<h2>Logged out.</h2>';
}

?>

<div class="login-container">

  <div id="login">
    <h1>Log in</h1>
    <form action="login.php" method="post">
      <span>Username:</span>
      <input type="text" name="username"><br><br>
      <span>Password:</span>
      <input type="password" name="password"><br><br>
      <input class="button" type="submit" name="submit">
    </form>
  </div> <!-- login -->

  <div id=register>
    <h1>Create account</h1>
    <form action="registered.php" method="post">
      <span>Username:</span>
      <input type="text" name="username"><br><br>
      <span>Password:</span>
      <input type="password" name="password"><br><br>
      <input class="button" type="submit" name="submit">
    </form>
  </div> <!-- register -->

</div> <!-- login-container -->


</body>
</html>