<?php 
    include("common.php");

    $usersList = getAllUsers();
    $leaderboard = array();

    // create an array of arrays of user info
    foreach($usersList as $user) {
        $userInfo = explode(";", $user);
        
        if(count($userInfo) < 4) {
            continue;
        }
        
        // remove password from array
        array_splice($userInfo, 1, 1);
        array_push($leaderboard, $userInfo);
    }

    // sort by descending order by number of wins
    usort($leaderboard, function ($a, $b) {
        return strnatcmp($b[1], $a[1]);
    });

    function build_table($array){
    // start table
    $html = '<table>';
    // header row
    $html .= '
    <tr>
    <th>Username</th>
    <th>Wins</th>
    <th>Losses</th>
    </tr>';

    // data rows
    foreach( $array as $key=>$value){
        $html .= '<tr>';
        foreach($value as $key2=>$value2){
            $html .= '<td>' . htmlspecialchars($value2) . '</td>';
        }
        $html .= '</tr>';
    }

    // finish table and return it

    $html .= '</table>';
    return $html;
}

?>

<!DOCTYPE html>
<html>
<head>
<style>
* {
    margin: 0;
    padding: 0;
}

a {
    padding: 8px;
}

h1 {
    text-align: center;
    margin: 40px;
}

table {
    width: 80%;
    margin: auto;
    border: 1px solid black;
}

th {
    background-color: #E0E0FF;
}

td {
    border: 1px solid #ddd;
    padding: 8px;
}

</style>
<title>Leaderboard</title>
</head>
<body>
<a href="./profile.php"><< Back to profile</a>
<h1>Leaderboard</h1>
<?php echo build_table($leaderboard); ?>
</body>
</html>