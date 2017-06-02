<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add movies</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

<?php

if(!$_SESSION['logged_in'] == true) {
    header("location: index.php");
}

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

    echo '<form action="" method="post">
    <input type="text" name="search_query">
    <input class="button" type="submit" value="Search">
    </form>';

    if (isset($_POST['search_query'])) {

        require '../keys/api.php';

        $search_query = rawurlencode($_POST['search_query']);

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_SSL_VERIFYPEER => false, // disable
          CURLOPT_URL => "https://api.themoviedb.org/3/search/movie?include_adult=false&page=1&query=$search_query&language=en-US&api_key=$tmdb_key",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_POSTFIELDS => "{}",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;

        } else {

            $json = json_decode($response, true);
            $movie = $json['results'][0];

            $title = $movie['title'];
            $type = 'movie';
            $year = substr($movie['release_date'], 0, 4);
            $poster_path = $movie['poster_path'];

            // checks movie table to see if record exists
            $check_query = "SELECT * FROM movie WHERE (title='$title' AND year='$year')";
            $check_response = @mysqli_query($dbc, $check_query);

            // stores movie_id and adds movie to table if not there
            if (mysqli_num_rows($check_response) > 0) { // row exists
                $movie_id = mysqli_fetch_array($check_response)['movie_id'];
            } else { // movie not in table
                @mysqli_query($dbc, "INSERT INTO movie (title, year, type)
                VALUES ('$title', '$year', '$type')");
                $movie_id = mysqli_insert_id($dbc); // gets last inserted ID
            }

            // adds entry to list if not there
            $exists_query = "SELECT * FROM entry_in_list WHERE (entry_in_list.list_id = $list_id AND entry_in_list.movie_id = $movie_id)";
            $exists_response = @mysqli_query($dbc, $exists_query);

            if (mysqli_num_rows($exists_response) > 0) { // row exists
                echo '<h3>Entry already in list.</h3>';
            } else {
                $entry_query = "INSERT INTO entry_in_list (list_id, movie_id)
                VALUES ($list_id, $movie_id)";
                @mysqli_query($dbc, $entry_query);
                $entry_id = mysqli_insert_id($dbc); // gets last inserted ID
                echo '<h3>Entry added to list.</h3>';
            }

        }
    
    }

    // creates table
    $query = "SELECT movie.title, movie.year
    FROM entry_in_list
    INNER JOIN movie ON entry_in_list.movie_id = movie.movie_id
    WHERE list_id=$list_id";
    $response = @mysqli_query($dbc, $query);

    echo '<table id="data-table" align="left"
    cellspacing="5" cellpadding="8">
    <tr>
    <th>Title</h>
    <th>Year</th>
    </tr>';

    while ($row = mysqli_fetch_array($response)) {
        echo '<tr>
        <td>' . $row['title'] . '</td>
        <td>' . $row['year'] . '</td>
        </tr>';
    }
    echo '</table>';

    mysqli_close($dbc);

} else { // id not in database or owned by other user
    echo '<h1>Not found.</h1>';
}

?>


</body>
</html>