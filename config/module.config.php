<?php

namespace Fabiang\Common\SwitchDatabase;

return [
    'switch-database' => [
        'connection_mapping' => [],
        'default_connection' => 'orm_default',
        'session_service'    => [
            'name' => null,
            'key'  => [
                'name' => null,
                'type' => 'property',
            ]
        ],
    ],
    'service_manager' => [
        'factories' => [
            'doctrine.connection.orm_default' => Doctrine\DefaultConnectionFactory::class,
        ]
    ]
];
