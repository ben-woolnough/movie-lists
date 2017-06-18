<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Movie Lists</title>
  <?php require 'templates/imports.html'; ?>
</head>

<body>

<?php

if($_SESSION['logged_in'] == true) {

    include 'templates/header.php';

    echo '<div id="content">';

    require_once('../../mysqli_connect.php');

    $user_id = $_SESSION['user_id'];

    // adds list to db
    if (isset($_POST['list_name'])) {

        if (strlen($_POST['list_name'])<1 OR preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['list_name'])) {

            echo '<h2 class="notify">Enter a valid name.</h2>';

        } else {
            $list_name = mysqli_real_escape_string($dbc, $_POST['list_name']);
            $create_list_query = "INSERT INTO list (list_name, user_id)
            VALUES ('$list_name', $user_id)";
            @mysqli_query($dbc, $create_list_query);
        }
    }

    echo '<h1>Create a new list</h1>
    <form action="profile.php" method="post">
      <input class="create-list" type="text" name="list_name" placeholder="Enter list name">
      <input class="button" type="submit" name="submit" value="Create">
    </form><br>';

    // gets list names for user
    $list_query = "SELECT * FROM list WHERE user_id='$user_id'";
    $list_response = @mysqli_query($dbc, $list_query);

    echo '<h1>Your Lists</h1>
    <table>
      <tr>
        <th>Name</th>
      </tr>';
    while ($row = mysqli_fetch_array($list_response)) {
        echo '<tr>
        <td><a href="listview.php?list_id=' . $row['list_id'] . '">' . 
        $row['list_name'] . '</a></td>
        </tr>';
    }
    echo '</table>';

    echo '</div> <!-- #content -->';

    mysqli_close($dbc);

} else {
    header("location: index.php");
}

?>

</body>
</html>