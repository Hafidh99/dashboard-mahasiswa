<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hash Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default hash driver that will be used to hash
    | passwords for your application. By default, the bcrypt algorithm is
    | used; however, you remain free to modify this option if you wish.
    |
    | Supported: "bcrypt", "argon", "argon2id"
    |
    */

    'driver' => 'mysql_legacy',

    /*
    |--------------------------------------------------------------------------
    | bcrypt Hashing Options
    |--------------------------------------------------------------------------
    |
    | Here you may configure the cost factor for the bcrypt algorithm used
    | by your application. This controls how many CPU cycles are spent
    | hashing a given password, so it is not recommended to reduce it.
    |
    */

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 12),
    ],

    /*
    |--------------------------------------------------------------------------
    | Argon Hashing Options
    |--------------------------------------------------------------------------
    |
    | Here you may configure the options for the Argon2 algorithm used by
    | your application. These let you control the amount of memory and
    | CPU cycles that are spent hashing a given password.
    |
    */

    'argon' => [
        'memory' => 65536,
        'threads' => 1,
        'time' => 4,
    ],

];