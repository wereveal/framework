<?php
/* Be sure to run this in the scripts dir */
if (!str_contains(__DIR__, '/src/scripts')) {
    die("Please Run this script from the /src/scripts directory\n");
}
$a_users = include '../config/people_config.php';
$hash = defined('PASSWORD_ARGON2I') ? PASSWORD_ARGON2I : PASSWORD_DEFAULT;
foreach ($a_users as $user => $password) {
    print $user . ': ' . password_hash($password, $hash) . "\n";
}

