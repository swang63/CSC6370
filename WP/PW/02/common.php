<?php

// User functions

function getUser($username) {
    $usersList = getAllUsers();

    # check if user already exists
    $user = null;
    
    foreach($usersList as $user) {
        $userInfo = explode(';', $user);

        if($username == $userInfo[0]) {
            return $userInfo;
        }
    }
}

function userExists($username) {
    $user = getUser($username);

    if($user == null) {
        return false;
    } else {
        return true;
    }
}

function getAllUsers() {
    return explode("\n", file_get_contents("./userinfo.txt"));
}

function userToString($user) {
    return "$user[0];$user[1];$user[2];$user[3]";
}

// User stats functions
// format: username;password;wins;losses

function addWin($username) {
    $content = file_get_contents("./userinfo.txt");

    $user = getUser($username);
    $old = userToString($user);

    $user[2]++;

    $new_content = str_replace($old, userToString($user), $content);
    file_put_contents("./userinfo.txt", $new_content);
}

function addLoss($username) {
    $content = file_get_contents("./userinfo.txt");

    $user = getUser($username);
    $old = userToString($user);

    $user[3]++;

    $new_content = str_replace($old, userToString($user), $content);
    file_put_contents("./userinfo.txt", $new_content);

}

?>