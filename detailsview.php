<?php require 'details.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo "$title ($year)" ?></title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>

<body>

<div class="container">

<br>
<div class="well">

    <div class="page-header text-center">
        <h1><?php echo $title ?> <small><?php echo $year ?></small></h1>
    </div>

    <div class="row">

        <div class="col-sm-3">
            <img class="img-responsive center-block" src="<?php echo $poster_path ?>" alt="poster">
        </div> <!-- .col-sm-3 -->

        <br>
        <div class="col-sm-9">
            <p><strong>Genres:</strong> <?php echo $genres ?></p>
            <p><strong>Runtime:</strong> <?php echo $length ?> minutes</p>
            <p><?php echo $overview ?></p>
            <a href="<?php echo $imdb_url ?>" target="_blank"><button class="btn btn-default">IMDb page</button></a>
        </div> <!-- .col-sm-9 -->

    </div> <!-- .row -->

</div> <!-- .well -->


<?php
// COMMENTS
require_once('../../mysqli_connect.php');

$query = "SELECT user, comment, timestamp
FROM comment
JOIN movie ON comment.movie_id = movie.movie_id
WHERE tmdb_id=$tmdb_id
ORDER BY timestamp DESC";

$response = mysqli_query($dbc, $query);

echo '<div class="well">
<h2 class="text-center">Comments</h2>
<div class="row">';
while ($row = mysqli_fetch_array($response)) {
    echo '<div class="col-sm-6 col-sm-offset-3 col-md-offset-2">
    <h3>'.$row['user'].' <small>'.$row['timestamp'].'</small></h3>
    <p>'.$row['comment'].'</p>
    </div>';
}
echo '</div></div>';

mysqli_close($dbc);
?>


</div> <!-- .container -->

</body>
</html>