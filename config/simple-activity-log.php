<?php

// config for Mrcookie/SimpleActivityLog
use Mrcookie\SimpleActivityLog\Loggers\ModelLogger;

return [
    /*
     * Enable or disable activity logging
     */
    'enabled' => env('SIMPLE_ACTIVITY_LOG_ENABLED', true),

    /*
     * Models to register for logging
     */
    'registered' => [
        // \App\Models\User::class,
    ],

    /*
     * The logger class to use for all registered models
     */
    'logger' => ModelLogger::class,

    /*
     * Default log name to use when not specified
     */
    'default_log_name' => 'model',

    /*
     * The attribute of the user model to use for causer identification
     */
    'causer_identifier' => 'email',

    /*
     * Events to log for each model
     * You can specify events for specific models or use a global setting
     * Leave empty to log all events (created, updated, deleted, restored, forceDeleted)
     */
    'events' => [
        // Model-specific events
        // \App\Models\User::class => [
        //     ObserverActionsEnum::CREATED->value,
        //     ObserverActionsEnum::UPDATED->value,
        // ],
    ],
];

