<?php
function redirect($url) {
    header("Location: $url");
    exit;
}

//password generetor
function generatePassword($length = 8) {
    return bin2hex(random_bytes($length / 2));
}

//validate datetime difference 
function validateDateDiff($date) {
    $now = new DateTime();
    $last = new DateTime($date);
    return $now->diff($last)->days > 30;
}
?>