<?php

if (isset($_POST['submit'])) {

    if (empty($_POST["username"]) OR empty($_POST["password"])) {

        header("Location: index.php?missing_data");
        exit();

    } else {

        require_once('../../mysqli_connect.php');

        $username = $_POST['username'];
        $password = $_POST['password'];

        // check that username does not contain special chars
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $username) OR strlen($username)>30 OR strlen($password)>30) {

            header("Location: index.php?invalid");
            exit();

        } else {

            $query = "INSERT INTO user (username, password) VALUES ('$username', '$password')";
            mysqli_query($dbc, $query);

            if (mysqli_affected_rows($dbc) == 1) {
                header("Location: index.php?registered=true");
                exit();
            } else {
                header("Location: index.php?registered=false");
                exit();
            }
            
        }

        mysqli_close($dbc);
    }

} else {
    header("Location: index.php");
    exit();
}

?>