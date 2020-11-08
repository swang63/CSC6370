<?php
    if(!isset($_SESSION['UserData']['username'])) {
        header("location: login.php");
    }
?>

<!DOCTYPE html>
<html>
<head>
<link href="gamestyles.css" type="text/css" rel="stylesheet">
</head>
<body>
<form action="game.php" method="post">
    <fieldset>
    <legend>OPTIONS</legend>
    Difficulty:
    <input name="difficulty" type=radio value="1" required>
    <label for="1">Easy</label>
    <input name="difficulty" type=radio value="2">
    <label for="2">Normal</label>
    <input name="difficulty" type=radio value="3">
    <label for="3">Difficult</label>
    <input name="difficulty" type=radio value="4">
    <label for="4">Extreme</label><br><br>
    Game Mode:
    <select name="mode">
    <option selected value="a">Single Word</option>
    <option value="b">Phrase</option>
    </select><br><br>
    Number of Hints:
    <select name="hints">
    <option selected>0</option>
    <option>1</option>
    <option>2</option>
    <option>3</option>
    </select><br><br>
    <button type="submit" name="newgame">New Game</button>
    </form>
    </fieldset>

    <a href="./profile.php"><< Back to profile</a>

</body>
</html>