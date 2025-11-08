<?php
return [
    'test_user' => [
        'email' => getenv('TEST_USER_EMAIL') ?: 'ken+test1@restobox.com',
        'pass'  => getenv('TEST_USER_PASS') ?: 'testpassword',
    ],
    'admin' => [
        'email' => getenv('ADMIN_EMAIL') ?: 'ken@restobox.com',
        'pass'  => getenv('ADMIN_PASS') ?: 'aBlhkA*Y3T1l63',
    ],
    // add more roles here
];
