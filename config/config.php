<?php

use TomaszBurzynski\CrudGenerator\Enums\DatabaseRelations;
use TomaszBurzynski\CrudGenerator\Enums\IdTypes;

return [
    /**
     * Models
     */
    'models' => [
        'User' => [ //Example model to create, based on default laravel user
            'columns' => [ //Columns for model
                'id' => [ //Primary Key column
                    'type' =>  IdTypes::standard, //Type of primary key, from TomaszBurzynski\CrudGenerator\Enums\IdTypes enum        
                ],
                'name' => [ //Column name
                    'type' => 'string', //For complete list of types go to documentation. Default 'string'.
                    'nullable' => false //If column is nullable, default false
                ],
                'lastName' => [
                    'type' => 'string',
                ],
                'email' => [
                    'type' => 'email',
                    'unique' => true, //If column must have unique value. Default false.
                ],
                'email_verified_at' => [
                    'type' => 'timestamp',
                    'nullable' => true,
                ],
                'password' => [
                    'type' => 'password',
                ],
                'roles' => [
                    'type' => 'relation', //Models relation
                    'relation' => [ //Relationship config. Check more in docs.
                        'id' => IdTypes::uuid,
                        'type' => DatabaseRelations::manyToMany, //Type of relation
                    ]
                ]
            ],
            'rememberToken' => true, //If model should use rememberToken column from https://laravel.com/docs/9.x/migrations#column-method-rememberToken. Default false.
            'timestamps' => true //If model should use timestamps from https://laravel.com/docs/9.x/migrations#column-method-timestamps. Default true.
        ],
        'Role' => [
            'columns' => [
                'id' => [
                    'type' => IdTypes::uuid,
                ],
                'name' => [],
                'visualText' => [],
                'users' => [
                    'type' => 'relation',
                    'relation' => [
                        'id' => IdTypes::uuid,
                        'type' => DatabaseRelations::manyToMany,
                    ]
                ]
            ],
        ]   
    ],
];