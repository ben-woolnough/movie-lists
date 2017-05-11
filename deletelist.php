<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
  <title>Test</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php

if($_SESSION['logged_in']==true AND $_SERVER['REQUEST_METHOD']=='POST') {

    include 'header.php';

    require_once('../../mysqli_connect.php');

    $list_id = $_POST['list_id'];

    // checks if list belongs to user
    $query = "SELECT * FROM list WHERE list_id=$list_id";
    $response = @mysqli_query($dbc, $query);
    $list_array = mysqli_fetch_array($response);

    if($_SESSION['user_id'] == $list_array['user_id']) {

        $delete_query = "DELETE FROM list WHERE list_id=$list_id";
        @mysqli_query($dbc, $delete_query);

        echo '<h1>List deleted.</h1>';
        echo '<h3><a href="profile.php">Back</a></h3>';

    } else {
        echo '<h1>Permission denied.</h1>';
    }

    mysqli_close($dbc);

} else {
    header("location: index.php");
}

?>


</body>
</html>