<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
  <title>Logged out</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

<h1>Logged out</h1>

<?php

session_unset();
session_destroy();
header("location: index.php?logout");

?>


</body>
</html>