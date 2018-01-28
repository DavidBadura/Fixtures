<?php declare(strict_types=1);
return [
            'user' =>
            [
                'properties' =>
                [
                    'class' => 'DavidBadura\\Fixtures\\TestObjects\\User',
                    'constructor' =>
                    [
                        0 => 'name',
                        1 => 'email',
                    ],
                ],
                'data' =>
                [
                    'david' =>
                    [
                        'name' => 'David Badura',
                        'email' => 'd.badura@gmx.de',
                        'group' =>
                        [
                            0 => '@group:owner',
                            1 => '@group:developer',
                        ],
                        'role' =>
                        [
                            0 => '@role:admin',
                        ],
                    ],
                    'other' =>
                    [
                        'name' => 'Somebody',
                        'email' => 'test@example.de',
                        'group' =>
                        [
                            0 => '@group:developer',
                        ],
                        'role' =>
                        [
                            0 => '@role:user',
                        ],
                    ],
                ],
            ],
        ];
