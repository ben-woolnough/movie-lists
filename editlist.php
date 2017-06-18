<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit</title>
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

    // gets list name and id
    $name_query = "SELECT * FROM list WHERE list_id=$list_id";
    $name_response = @mysqli_query($dbc, $name_query);
    $list_array = mysqli_fetch_array($name_response);
    $list_name = $list_array['list_name'];

    // checks if list belongs to user
    if ($_SESSION['user_id'] == $list_array['user_id']) {

        // rename list and update list_name variable
        if (isset($_POST['newname'])) {

            if (strlen($_POST['newname'])<1 OR preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['newname'])) {

                echo '<h3 class="notify">Enter a valid name.</h3>';

            } else {
                $newname = mysqli_real_escape_string($dbc, $_POST['newname']);
                $rename_query = "UPDATE list SET list_name='$newname' WHERE list_id=$list_id";
                @mysqli_query($dbc, $rename_query);
                $list_name = $newname;
            }
        }

        // deletes entry from list
        if (isset($_POST['id_to_delete'])) {
            $id_to_delete = mysqli_real_escape_string($dbc, $_POST['id_to_delete']);
            $delete_entry = "DELETE FROM entry_in_list
            WHERE (list_id=$list_id AND movie_id=$id_to_delete)";
            @mysqli_query($dbc, $delete_entry);
        }

        // edit comment form
        if (isset($_POST['new_comment'])) {
            // update if comment exists; insert if not

            $new_comment = htmlspecialchars(mysqli_real_escape_string($dbc, $_POST['new_comment']));
            $comment_entry_id = mysqli_real_escape_string($dbc, $_POST['entry_id']);

            $query = "SELECT * FROM comment WHERE entry_id=$comment_entry_id";
            $response = @mysqli_query($dbc, $query);
            $comment_array = mysqli_fetch_array($response);

            if ($comment_array) {
                $update_query = "UPDATE comment SET comment='$new_comment' WHERE entry_id=$comment_entry_id";
                @mysqli_query($dbc, $update_query);
            } else {
                $insert_query = "INSERT INTO comment (comment, entry_id)
                VALUES ('$new_comment', $comment_entry_id)";
                @mysqli_query($dbc, $insert_query);
            }
        }

        echo '<h1> Editing: ' . $list_name . '</h1>';

        echo '<a href="listview.php?list_id=' . $list_id . '"><button class="button">Done editing</button></a>';

        // delete list form
        echo '<div>
        <form style="display:inline" action="deletelist.php" method="post">
        <button class="button" type="submit" name="list_id" value="' . $list_id . '" style="background: #d22">Delete list</button>
        </form>';

        // rename form
        echo '<span><button onclick="renameList()" class="button">Rename list</button></span>
        
        
        <form action="editlist.php?list_id=' . $list_id . '" method="post" style="display:inline">
        <input class="rename" style="display:none" type="text" name="newname" placeholder="Enter a name">
        <button class="button rename" style="display:none" type="submit" name="list_id" value="submit">OK</button>
        </form>
        </div>';


        /* TABLE */

        // joins entries and movies to get titles
        $query = "SELECT entry_in_list.entry_id, movie.movie_id, movie.title, movie.year, movie.type, comment.comment, comment.comment_id
        FROM entry_in_list
        LEFT JOIN comment ON entry_in_list.entry_id = comment.entry_id
        INNER JOIN movie ON entry_in_list.movie_id = movie.movie_id
        WHERE list_id=$list_id";
        $response = @mysqli_query($dbc, $query);

        echo '<table align="left"
        cellspacing="5" cellpadding="8">
        <tr>
        <th>Title</h>
        <th>Year</th>
        <th>Type</th>
        <th>Comment</th>
        <th>Delete</th>
        </tr>';

        while ($row = mysqli_fetch_array($response)) {
            echo '<tr>
            <td>' . $row['title'] . '</td>
            <td>' . $row['year'] . '</td>
            <td>' . $row['type'] . '</td>
            <td><p class="prewrap">' . $row['comment'] . '</p><button onclick="editComment(this)" class="button edit-button" value=' . $row['entry_id'] . '>Edit</button></td>
            <td>
              <form action="editlist.php?list_id=' . $list_id . '" method="post">
                <button class="button delete-button" type="submit" name="id_to_delete" value=' . $row['movie_id'] . '>&times</button>
              </form>
            </td>
            </tr>';
        }
        echo '</table>';

        // comment form
        echo '<div id="comment-area">
        <div id="comment-content">
        <button onclick="closeModal()" class="close">&times;</button>
        <form action="editlist.php?list_id=' . $list_id . '" method="post">
        <textarea name="new_comment" rows="30"></textarea>
        <button onclick="closeModal" class="button" type="submit" name="entry_id">Save</button>
        </form>
        </div>
        </div>';

    } else { // id not in database or owned by other user
        echo '<h1>Not found.</h1>';
    }

    echo '</div> <!-- #content -->';

    mysqli_close($dbc);

} else {
    header("location: index.php");
}

?>

<script src="js/script.js"></script>

</body>
</html>