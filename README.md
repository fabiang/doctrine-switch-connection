# fabiang/doctrine-switch-connection

[![Build Status](https://travis-ci.com/fabiang/doctrine-switch-connection.svg?branch=master)](https://travis-ci.com/fabiang/doctrine-switch-connection)

This Laminas and Zend Framework 3 module helps you to switch your Doctrine connection depending on some others object value.

```bash
composer require fabiang/doctrine-switch-connection
```

Add the module to your `application.config.php`:

```php
<?php

return [
    'modules'                 => [
        /** more modules */
        'Fabiang\Common\SwitchDatabase',
    ],
];
```

Make sure you have `DoctrineORMModule` loaded before, as this module depends on it.

## Configuration

Configure your `doctrine.local.php` like the following:

```php
<?php

use Doctrine\DBAL\Driver\PDOMySql\Driver as MySQLDriver;
use Fabiang\Common\SwitchDatabase\Doctrine\ConnectionFactory;

return [
    'doctrine' => [
        // configure your database connections
        'connection' => [
            // you then use orm_default and
            'orm_conn1' => [
                'driverClass' => MySQLDriver::class,
                'params'      => [
                    'host'     => getenv('DB_CONN1_HOST'),
                    'port'     => getenv('DB_CONN1_PORT'),
                    'user'     => getenv('DB_CONN1_USER'),
                    'password' => getenv('DB_CONN1_PASSWORD'),
                    'dbname'   => getenv('DB_CONN1_DBNAME'),
                    'charset'  => 'utf8mb4',
                ]
            ],
            'orm_conn2' => [
                'driverClass' => MySQLDriver::class,
                'params'      => [
                    'host'     => getenv('DB_CONN2_HOST'),
                    'port'     => getenv('DB_CONN2_PORT'),
                    'user'     => getenv('DB_CONN2_USER'),
                    'password' => getenv('DB_CONN2_PASSWORD'),
                    'dbname'   => getenv('DB_CONN2_DBNAME'),
                    'charset'  => 'utf8mb4',
                ]
            ],
        ]
    ],
    'service_manager' => [
        // configure your connection factories
        'factories'          => [
            'doctrine.connection.orm_conn1' => ConnectionFactory::class,
            'doctrine.connection.orm_conn2' => ConnectionFactory::class,
        ]
    ],
    'switch-database' => [
        // map value received from object to connection name
        'connection_mapping' => [
            'Fabiang' => 'orm_conn2',
            'Test1'   => 'orm_conn1',
            'Test2'   => 'orm_conn1',
        ],
        'default_connection' => 'orm_conn1',
        // configure the object, from whom to receive the value
        // the value returned is used by `connection_mapping` to map
        // the value to the connection name
        'session_service'    => [
            // the service name to receive from container
            'name' => 'autentication_storage_session',
            'key'  => [
                'name' => 'companyBrand', // property or method name
                'type' => 'property', // 'property' or 'method'
            ]
        ],
    ],
];

```
