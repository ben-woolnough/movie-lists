<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
  <title>Movie Lists</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php

if($_SESSION['logged_in'] == true) {

    include 'header.php';

    require_once('../../mysqli_connect.php');

    $user_id = $_SESSION['user_id'];

    //print_r($_SESSION);

    // adds list to db
    if(isset($_POST['list_name'])) {
        $list_name = $_POST['list_name'];
        $create_list_query = "INSERT INTO list (list_name, user_id)
        VALUES ('$list_name', $user_id)";
        //TODO: variable not needed (others?)
        $create_list_response = @mysqli_query($dbc, $create_list_query);
    }

    echo '<h3>Create a new list</h3>
    <form action="profile.php" method="post">
      <input class=input type="text" name="list_name" placeholder="Enter a name">
      <input class="button" type="submit" name="submit" value="Create">
    </form><br>';

    // gets list names for user
    $list_query = "SELECT * FROM list WHERE user_id='$user_id'";
    $list_response = @mysqli_query($dbc, $list_query);

    echo '<h3>Your Lists</h3>
    <table>
      <tr>
        <th>Name</th>
      </tr>';
    while($row = mysqli_fetch_array($list_response)) {
        echo '<tr>
        <td><a href="listview.php?list_id=' . $row['list_id'] . '">' . 
        $row['list_name'] . '</a></td>
        </tr>';
    }
    echo '</table>';

    mysqli_close($dbc);

} else {
    header("location: index.php");
}

?>


</body>
</html>