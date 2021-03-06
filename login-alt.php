<?php

session_start();

require_once('../../mysqli_connect.php');

if (isset($_POST['submit'])) { // check form was submitted

    if (empty($_POST["username"]) OR empty($_POST["password"])) {

        header("Location: index.php?missing_data");
        exit();

    } else {

        $username = mysqli_real_escape_string($dbc, $_POST['username']);
        $password = mysqli_real_escape_string($dbc, $_POST['password']);

        $stmt = mysqli_prepare($dbc, "SELECT * FROM user WHERE username=? AND password=?");

        mysqli_stmt_bind_param($stmt, "ss", $username, $password);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        $user_array = mysqli_fetch_array($result);

        if ($user_array) {
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user_array['user_id'];
            $_SESSION['logged_in'] = true;
            header("Location: profile.php");
            exit();
                
        } else {
            header("Location: index.php?wrong_password");
            exit();
        }

    }

} else { // no submit
    header("Location: index.php");
    exit();
}

mysqli_close($dbc);

?>