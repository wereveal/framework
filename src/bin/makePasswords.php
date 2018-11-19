<?php
$a_users = [
    'SuperAdmin' => 'letGSAin',
    'Admin'      => 'letADMin',
    'Manager'    => 'letMANin'
];
$hash = \defined('PASSWORD_ARGON2I') ? PASSWORD_ARGON2I : PASSWORD_DEFAULT;
foreach ($a_users as $user => $password) {
    print $user . ': ' . password_hash($password, $hash) . "\n";
}

