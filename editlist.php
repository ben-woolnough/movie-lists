<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php

if($_SESSION['logged_in'] == true) {

    include 'header.php';

    require_once('../../mysqli_connect.php');

    if(isset($_GET['list_id'])) { // if list_id not set, redirect
        $list_id = $_GET['list_id'];
    } else {
        header("location: profile.php");
    }

    // gets list name and id
    $name_query = "SELECT * FROM list WHERE list_id=$list_id";
    $name_response = @mysqli_query($dbc, $name_query);
    $list_array = mysqli_fetch_array($name_response);
    $list_name = $list_array['list_name'];

    // checks if list belongs to user
    if($_SESSION['user_id'] == $list_array['user_id']) {

        // updates list name
        if(isset($_POST['newname'])) {
            $rename_query = "UPDATE list SET list_name='" . $_POST['newname'] . "' WHERE list_id=$list_id";
            @mysqli_query($dbc, $rename_query);
            $list_name = $_POST['newname'];
        }

        // deletes entry from list
        if(isset($_POST['id_to_delete'])) {
            $delete_entry = "DELETE FROM entry_in_list
            WHERE (list_id=$list_id AND movie_id=" . $_POST['id_to_delete'] . ")";
            @mysqli_query($dbc, $delete_entry);
        }

        // edit comment form
        if(isset($_POST['new_comment'])) {
            // update if comment exists; insert if not

            $new_comment = htmlspecialchars($_POST['new_comment']);

            $query = "SELECT * FROM comment WHERE entry_id=" . $_POST['entry_id'];
            $response = @mysqli_query($dbc, $query);

            $comment_array = mysqli_fetch_array($response);
            if($comment_array) {
                $update_query = "UPDATE comment SET comment='" . $new_comment . "' WHERE entry_id=" . $_POST['entry_id'];
                @mysqli_query($dbc, $comment_query);
            } else {
                $insert_query = "INSERT INTO comment (comment, entry_id)
                VALUES ('$new_comment'," . $_POST['entry_id'] . ")";
                @mysqli_query($dbc, $insert_query);
            }
        }

        echo '<h1> Editing: ' . $list_name . '</h1>';

        echo '<h3><a href="listview.php?list_id=' . $list_id . '">Done editing</a></h3>';

        // delete list form
        echo '<form action="deletelist.php" method="post">
        <button class=button type="submit" name="list_id" value="' . $list_id . '" style="background: #d22">Delete list</button>
        </form>';

        // rename form
        echo '<button onclick="renameList()" class="button">Rename list</button>
        
        <form action="editlist.php?list_id=' . $list_id . '" method="post" style="display:inline">
        <input class="rename" style="display:none" type="text" name="newname" placeholder="Enter a name">
        <button class="button rename" style="display:none" type="submit" name="list_id" value="submit">OK</button>
        </form>';

        // HTML searchbar
        echo '<div>
          <input id="search-input" class="input" type="text" name="search" placeholder="Add movies or TV series">
          <button class="button" onclick="getSearch()">Search</button>
        </div>';

        // HTML box
        echo '<div id=box>
          <div id=box-left>
          <h3>title year type</h3>
          <img src="" alt="Poster" height="226" width="150">

          <form id="hidden-form" action="editlist.php?list_id=' . $list_id . '" method="post">
            <p>Title: <input type="text" name="title" size="50" value=""></p>
            <p>Year: <input type="text" name="year" size="4" value=""></p>
            <p>Type (movie/series): <input type="text" name="type" size="6" value=""></p>
          </div> <!-- box-left -->

          <div id="box-right">
            <h4>Comments:</h4>
            <textarea type="text" name="comment" rows="13" cols="35" placeholder="(Optional) Enter a comment..."></textarea>
            <div><input id="add-button" class="button" type="submit" name="submit" value="Add"></div>
          </form>
          </div> <!-- box-right -->
        </div>';

        if(isset($_POST['submit']) AND isset($_POST['title'])) {

            $title = $_POST['title'];
            $year = $_POST['year'];
            $type = $_POST['type'];
            $comment = htmlspecialchars($_POST['comment']);

            //print_r($_POST);

            // checks movie table to see if record exists
            $check_query = "SELECT * FROM movie WHERE (title='$title' AND year='$year')";
            $check_response = @mysqli_query($dbc, $check_query);
            

            // stores movie_id and adds movie to table if not there
            if(mysqli_num_rows($check_response) > 0) { // row exists
                $movie_id = mysqli_fetch_array($check_response)['movie_id'];
            } else { // movie not in table
                @mysqli_query($dbc, "INSERT INTO movie (title, year, type)
                VALUES ('$title', '$year', '$type')");
                $movie_id = mysqli_insert_id($dbc); // gets last inserted ID
            }

            // adds entry to list if not there
            $exists_query = "SELECT * FROM entry_in_list WHERE (entry_in_list.list_id = $list_id AND entry_in_list.movie_id = $movie_id)";
            $exists_response = @mysqli_query($dbc, $exists_query);
            if(mysqli_num_rows($exists_response) > 0) { // row exists
                echo '<br><br>Movie in list.<br><br>';
            } else { // doesn't exist
                $entry_query = "INSERT INTO entry_in_list (list_id, movie_id)
                VALUES ($list_id, $movie_id)";
                @mysqli_query($dbc, $entry_query);
                $entry_id = mysqli_insert_id($dbc); // gets last inserted ID
                //echo "<h1>Entry ID: $entry_id</h1>";
                echo 'Entry added to list.<br><br>';

                // adds comment to table
                if(!$comment == "") {
                    @mysqli_query($dbc, "INSERT INTO comment (comment, entry_id)
                        VALUES ('$comment', $entry_id)");
                }
            }
        }

        // creates table
        // joins entries and movies to get titles
        $query = "SELECT entry_in_list.entry_id, movie.movie_id, movie.title, movie.year, movie.type, comment.comment, comment.comment_id
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
        <th>Delete</th>
        </tr>';

        while($row = mysqli_fetch_array($response)) {
            echo '<tr>
            <td>' . $row['title'] . '</td>
            <td>' . $row['year'] . '</td>
            <td>' . $row['type'] . '</td>
            <td><p class="prewrap">' . $row['comment'] . '</p><button onclick="editComment(this)" class="edit-button" value=' . $row['entry_id'] . '>Edit</button></td>
            <td>
              <form action="editlist.php?list_id=' . $list_id . '" method="post">
                <button class="delete-button" type="submit" name="id_to_delete" value=' . $row['movie_id'] . '>x</button>
              </form>
            </td>
            </tr>';
        }
        echo '</table>';

        // comment form
        echo '<div id="comment-area">
        <form action="editlist.php?list_id=' . $list_id . '" method="post">
        <textarea name="new_comment" rows="30" cols="60"></textarea>
        <button class="button" type="submit" name="entry_id">Save</button>
        </form>
        </div>';

    mysqli_close($dbc);

    } else { // id not in database or owned by other user
        echo '<h1>Not found.</h1>';
    }

} else {
    header("location: index.php");
}

?>

<script src="script.js"></script>

</body>
</html>