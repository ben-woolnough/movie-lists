<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
  <title>Test</title>
  <?php require 'templates/imports.html'; ?>
</head>

<body>

<?php

if($_SESSION['logged_in']==true AND $_SERVER['REQUEST_METHOD']=='POST') {

    include 'templates/header.php';

    require_once('../../mysqli_connect.php');

    $list_id = mysqli_real_escape_string($dbc, $_POST['list_id']);

    // checks if list belongs to user
    $query = "SELECT * FROM list WHERE list_id=$list_id";
    $response = mysqli_query($dbc, $query);
    $list_array = mysqli_fetch_array($response);

    if($_SESSION['user_id'] == $list_array['user_id']) {

        $delete_query = "DELETE FROM list WHERE list_id=$list_id";
        mysqli_query($dbc, $delete_query);

        echo '<h2 class="notify">List deleted.</h2>';
        echo '<a href="profile.php"><button class="button">Back</button></a>';

    } else {
        echo '<h2 class="notify">Permission denied.</h2>';
    }

    mysqli_close($dbc);

} else {
    header("location: index.php");
}

?>


</body>
</html>