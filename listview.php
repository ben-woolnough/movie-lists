<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>List</title>
  <?php require 'templates/imports.html'; ?>
</head>

<body>

<?php

if ($_SESSION['logged_in'] == true) {

    include 'templates/header.php';

    echo '<div id="content">';

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
        echo '<a href="editlist.php?list_id=' . $list_id . '"><button class="button">Edit</button></a>';
        echo '<a href="add.php?list_id=' . $list_id . '"><button class="button">Add movies</button></a>';

        // creates table
        $query = "SELECT movie.tmdb_id, movie.title, movie.year, comment.comment
        FROM entry_in_list
        LEFT JOIN comment ON entry_in_list.entry_id = comment.entry_id
        INNER JOIN movie ON entry_in_list.movie_id = movie.movie_id
        WHERE list_id=$list_id";
        $response = @mysqli_query($dbc, $query);

        echo '<table align="left"
        cellspacing="5" cellpadding="8">
        <tr>
        <th>Title</h>
        <th>Comment</th>
        </tr>';

        while ($row = mysqli_fetch_array($response)) {
            echo '<tr>
            <td><a href="detailsview.php?q=' . $row['tmdb_id'] . '" target="_blank">' . $row['title'] .' ('.$row['year']. ')' . '</a></td>
            <td><p class="prewrap">' . $row['comment'] . '</p></td>
            </tr>';
        }
        echo '</table>';

        mysqli_close($dbc);

    } else { // id not in database or owned by other user
        echo '<h1>Not found.</h1>';
    }

    echo '</div> <!-- #content --> ';

} else {
    header("location: index.php");
}

?>

</body>
</html>