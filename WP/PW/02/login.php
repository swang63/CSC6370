<?php
    session_save_path("./session");
    session_start();
    include('common.php');

    if(isset($_SESSION['UserData']['username'])) {
        header("location:profile.php");
        exit;
    }

    if(isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $exists = userExists($username);

        if($exists) {
            $user = getUser($username);

            if($password == $user[1]) {
                $_SESSION['UserData']['username'] = $username;
                header("location:profile.php");
                exit;
            } else {
                echo "Invalid Credentials";
            }
        } else {
            echo "Invalid Credentials";
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
<title>Login</title>
</head>
<body>
<fieldset>
    <h1>
        Login
    </h1>
<form action="" method="post" >
<label for="username">Username:</label>
<input type="text" placeholder="Username" name="username" size="20">
<br>
<label for="password">Password:</label>
<input type="password" placeholder="Password" name="password" size="20">
<br>
<input class="button" type="submit" value="Login">
</form>
<p>Don't have an account? Register <a href="./register.php">here</a></p>
</fieldset>
</body>