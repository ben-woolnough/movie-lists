<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
  <title></title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>


<?php
if(isset($_POST['submit'])) { // check form was submitted
    if(empty($_POST["username"]) OR empty($_POST["password"])) {
        header("location: index.php?missing_data");
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
                header("location: profile.php");
                
            } else {
                // wrong password
                header("location: index.php?login_failed");
            }
        } else {
            // wrong username
            header("location: index.php?login_failed");
        }

        mysqli_close($dbc);

    }
} else { // no submit
    header("location: index.php");
}
?>


</body>
</html>