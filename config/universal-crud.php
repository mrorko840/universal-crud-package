<?php

return [

    'base_uri' => 'universal-crud',

    'auth_middleware' => ['api', 'auth:sanctum'],

    'allowed_tables' => ['users', 'posts', 'products'],

    'hidden_columns' => ['password', 'remember_token'],

    'upload_disk' => 'public',

    'upload_base_path' => 'uploads',
];
