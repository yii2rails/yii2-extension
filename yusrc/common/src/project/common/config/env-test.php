<?php

return [
    'servers' => [
        'core' => null,
        'db' => [
            'main' => [
                'driver' => 'sqlite',
                'dbname' => '@common/runtime/sqlite/test.db',
                'map' => null,
            ],
        ],
    ],
    'mode' => [
        'env' => 'test',
        'debug' => true,
    ],
    'jwt' => [
        'profiles' => [
            'default' => [
                'key' => 'IeWUAWlY55VVPOEOtbp1PH1NXOotU0Wh',
            ],
            'auth' => [
                'key' => 'W4PpvVwI82Rfl9fl2R9XeRqBI0VFBHP3',
            ],
        ],
    ],
    'domain' => [
        'driver' => [
            'primary' => 'ar',
            'slave' => 'ar',
        ],
    ],
];