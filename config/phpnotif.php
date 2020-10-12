<?php

return [
    'connection' => [
        'mongodb' => [
            'host' => env('MONGO_HOST'),
            'port' => env('MONGO_PORT'),
            'database' => env('MONGO_DATABASE'),
            'username' => env('MONGO_USERNAME'),
            'password' => env('MONGO_PASSWORD'),
            'options' => [
                'database' => 'admin' // sets the authentication database required by mongo 3
            ],
            'default_collection_name' => 'user_inboxes'
        ]
    ],
    'pagination' => [
        'per_page' => 10
    ]
];
