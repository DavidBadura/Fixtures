<?php

return array(
            'user' =>
            array(
                'properties' =>
                array(
                    'class' => 'DavidBadura\\Fixtures\\TestObjects\\User',
                    'constructor' =>
                    array(
                        0 => 'name',
                        1 => 'email',
                    ),
                ),
                'data' =>
                array(
                    'david' =>
                    array(
                        'name' => 'David Badura',
                        'email' => 'd.badura@gmx.de',
                        'group' =>
                        array(
                            0 => '@group:owner',
                            1 => '@group:developer',
                        ),
                        'role' =>
                        array(
                            0 => '@role:admin',
                        ),
                    ),
                    'other' =>
                    array(
                        'name' => 'Somebody',
                        'email' => 'test@example.de',
                        'group' =>
                        array(
                            0 => '@group:developer',
                        ),
                        'role' =>
                        array(
                            0 => '@role:user',
                        ),
                    ),
                ),
            )
        );
