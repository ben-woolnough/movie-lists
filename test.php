<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Test</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

<?php

echo $_SESSION['username'];

?>


</body>
</html>