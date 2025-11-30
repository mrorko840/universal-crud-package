<?php

return [

    'base_uri' => 'universal-crud',

    'auth_middleware' => ['api'],

    'allowed_tables' => ['*'], // all tables will be allowed   

    'hidden_columns' => [], // all columns will be hidden

    'upload_disk' => 'public', // disk name

    'upload_base_path' => 'uploads', // base path
];
