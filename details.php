<?php

function getMovieInfo($tmdb_id) {

    require '../keys/api.php';

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.themoviedb.org/3/movie/$tmdb_id?language=en-US&api_key=$tmdb_key",
    CURLOPT_SSL_VERIFYPEER => false,
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
    }

    $json = json_decode($response, true);
    return $json;
}

if (isset($_GET['q'])) {
    $tmdb_id = $_GET['q'];
    if (is_numeric($tmdb_id)) {

        $res = getMovieInfo($tmdb_id);

        $fields = ['title', 'release_date', 'runtime', 'genres',
        'overview', 'poster_path', 'imdb_id'];
        $missing_data = [];
        foreach ($fields as $field) {
            if (!isset($res[$field])) {
                array_push($missing_data, $field);
            }
        }

        if (empty($missing_data)) {
            $title = htmlspecialchars($res['title']);
            $year = substr($res['release_date'], 0, 4);
            $length = $res['runtime'];

            for ($i = 0; $i < count($res['genres']); $i++) {
                $genre_array[$i] = $res['genres'][$i]['name'];
            }
            $genres = implode(', ', $genre_array);
            
            $overview = htmlspecialchars($res['overview']);
            $poster_path = "http://image.tmdb.org/t/p/w185" . $res['poster_path'];
            $imdb_url = "http://www.imdb.com/title/" . $res['imdb_id'];
        } else {
            exit('Could not get a valid response.');
        }

    } else {
        exit('Query must be an integer');
    }
}

if (!isset($res)) {
    exit('Could not get a response.');
}

?>