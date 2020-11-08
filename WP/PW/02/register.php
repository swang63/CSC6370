<?php
    session_save_path("./session");
    session_start();
    include('common.php');

    if(isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $exists = userExists($username);

        // format for user information
        // username;password;wins;losses
        if(!$exists) {
            file_put_contents("./userinfo.txt", "$username;$password;0;0\n", FILE_APPEND);

            // new user so default value for wins/losses is 0
            $_SESSION['UserData']['username'] = $username;

            header("location:profile.php");
            exit;
        } else {
            echo "User already exists.";
        }
    
    }
?>

<!DOCTYPE html>
<html>
<head>
<link href="gamestyles.css" type="text/css" rel="stylesheet">
<style>
    input {
        margin: 5px;
    }

    fieldset {
        margin: 80px auto;
    }

    .button {
        margin-left: 60px;
    }

    fieldset input {
        width: 80%;
        margin: 10 auto;
    }

    fieldset h1, a, p {
        text-align: center;
    }
</style>
<title>Registration</title>
</head>
<body>
<fieldset>
    <h1>
        Register an account
    </h1>
<form action="" method="post" >
<label for="username">Username:</label>
<input type="text" placeholder="Username" name="username" pattern='[A-Za-z0-9]*' size='20'>
<br>
<label for="password">Password:</label>
<input type="password" placeholder="Password" name="password" pattern='[A-Za-z0-9]*' size='20'>
<br>
<input class="button" type="submit" value="Register">
</form>
<p>Already have an account? Log in <a href="./login.php">here</a></p>
</fieldset>
</body>
</html>