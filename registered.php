<!DOCTYPE html>
<html>
<head>
  <title></title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php

if(isset($_POST['submit'])) {

    if(empty($_POST["username"]) OR empty($_POST["password"])) {

        header("location: index.php?missing_data");

    } else {

        require_once('../../mysqli_connect.php');

        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $query = "INSERT INTO user (username, password) VALUES ('$username', '$password')";
        @mysqli_query($dbc, $query);

        if(mysqli_affected_rows($dbc) == 1) {
            header("location: index.php?registered=true");
        } else {
            header("location: index.php?registered=false");
        }

        mysqli_close($dbc);
    }

} else {
    header("location: index.php");
}

?>

</body>
</html>