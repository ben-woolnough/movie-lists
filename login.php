<?php
session_start();

if(isset($_POST['submit'])) { // check form was submitted
    if(empty($_POST["username"]) OR empty($_POST["password"])) {
        header("Location: index.php?missing_data");
        exit();
    } else {
        require_once('../../mysqli_connect.php');
        
        $username = mysqli_real_escape_string($dbc, $_POST['username']);
        $password = mysqli_real_escape_string($dbc, $_POST['password']);

        $query = "SELECT * FROM user WHERE username='$username'";
        $response = @mysqli_query($dbc, $query);

        
        if(mysqli_num_rows($response) == 1) { // user exists
            $user_array = mysqli_fetch_array($response);
            if($_POST['password'] == $user_array['password']) {
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user_array['user_id'];
                $_SESSION['logged_in'] = true;
                header("Location: profile.php");
                exit();
                
            } else {
                // wrong password
                header("Location: index.php?login_failed");
                exit();
            }
        } else {
            // wrong username
            header("Location: index.php?login_failed");
            exit();
        }

        mysqli_close($dbc);

    }
} else { // no submit
    header("Location: index.php");
    exit();
}
?>