<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
  <title>List</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php

if ($_SESSION['logged_in'] == true) {

    include 'header.php';

    require_once('../../mysqli_connect.php');
    
    // validate list_id
    if (isset($_GET['list_id'])) { // check list_id set
        $list_id = $_GET['list_id'];
        if (!is_numeric($list_id)) { // if list_id not a number, redirect
            header("location: profile.php");
        }
    } else {
        header("location: profile.php");
    }
        
    // gets list name
    $name_query = "SELECT * FROM list WHERE list_id=$list_id";
    $name_response = @mysqli_query($dbc, $name_query);
    $list_array = mysqli_fetch_array($name_response);
    $list_name = $list_array['list_name'];

    // checks if list belongs to user
    if ($_SESSION['user_id'] == $list_array['user_id']) {

        echo '<h1> List: ' . $list_name . '</h1>';
        echo '<h3><a href="editlist.php?list_id=' . $list_id . '">Edit</a></h3>';

        // creates table
        $query = "SELECT movie.title, movie.year, movie.type, comment.comment
        FROM entry_in_list
        LEFT JOIN comment ON entry_in_list.entry_id = comment.entry_id
        INNER JOIN movie ON entry_in_list.movie_id = movie.movie_id
        WHERE list_id=$list_id";
        $response = @mysqli_query($dbc, $query);

        echo '<table id="data-table" align="left"
        cellspacing="5" cellpadding="8">
        <tr>
        <th>Title</h>
        <th>Year</th>
        <th>Type</th>
        <th>Comment</th>
        </tr>';

        while ($row = mysqli_fetch_array($response)) {
            echo '<tr>
            <td>' . $row['title'] . '</td>
            <td>' . $row['year'] . '</td>
            <td>' . $row['type'] . '</td>
            <td><p class="prewrap">' . $row['comment'] . '</p></td>
            </tr>';
        }
        echo '</table>';

        mysqli_close($dbc);

    } else { // id not in database or owned by other user
        echo '<h1>Not found.</h1>';
    }

} else {
    header("location: index.php");
}

?>


</body>
</html>