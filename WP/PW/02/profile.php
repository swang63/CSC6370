<?php
    session_save_path("./session");
    session_start();
    include('common.php');

    if(!isset($_SESSION['UserData']['username'])) {
        header("location:login.php");
        exit;
    }

    $user = getUser($_SESSION['UserData']['username']);
?>

<!DOCTYPE html>
<html>
    <style>
        body {
            width: 600px;
            margin: 80px auto;
        }

        h1 {
            text-align: center;
        }

        .logout {
            float: right;
        }
    </style>
    <head>
        <link href="gamestyles.css" type="text/css" rel="stylesheet">
        <title><?= $user[0] ?>'s Homepage</title>
    </head>
    <body>
    <fieldset>
        <h1>Welcome <?= $user[0] ?>!</h1>
        <p>Wins: <?= $user[2] ?></p>
        <p>Losses: <?= $user[3] ?></p>
        <p>Total games: <?= $user[2]+$user[3] ?></p>
        <p>Win Rate: <?= ($user[2]+$user[3]) != 0 ? number_format((float)$user[2]/($user[2]+$user[3])*100, 2, '.', '') : "0" ?>%</p>
        <br>
        <p><a href="./leaderboard.php">Leaderboard</a></p>
        <p><a href="./game.php">Create Game</a></p>
        <a class='logout' href="./logout.php">Logout</a>
    </fieldset>
    </body>
</html>