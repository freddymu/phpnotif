<?php

return [
    'connection' => [
        'mongodb' => [
            'host' => '172.31.0.10',
            'port' => 27017,
            'database' => 'phpnotif',
            'username' => 'mongo-root',
            'password' => 'MolaD1nDevr00t!n3w',
            'options' => [
                'database' => 'admin' // sets the authentication database required by mongo 3
            ],
            'default_collection_name' => 'user_inboxes'
        ]
    ]
];
