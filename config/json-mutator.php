<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default JSON Options
    |--------------------------------------------------------------------------
    |
    | Default JSON encoding options for the JSON mutator.
    | You can customize these options as needed.
    |
    */
    'json_options' => JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,

    /*
    |--------------------------------------------------------------------------
    | Default Values
    |--------------------------------------------------------------------------
    |
    | Default values for new metadata instances.
    |
    */
    'defaults' => [
        'empty_value' => '{}',
        'null_value' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    |
    | Validation rules for metadata values.
    |
    */
    'validation' => [
        'max_depth' => 10,
        'max_length' => 65535,
    ],
];

