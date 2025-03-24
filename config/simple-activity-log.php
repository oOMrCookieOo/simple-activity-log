<?php

// config for Mrcookie/SimpleActivityLog
use Mrcookie\SimpleActivityLog\Loggers\ModelLogger;

return [
    /*
    |--------------------------------------------------------------------------
    | Activity Logger Configuration
    |--------------------------------------------------------------------------
    |
    | This file controls the behavior of the Simple Activity Log 2 package.
    | You can register models to be automatically observed, customize the
    | logger class, and configure various logging options.
    |
    */


    /*
    |--------------------------------------------------------------------------
    | Enable/Disable Logging
    |--------------------------------------------------------------------------
    |
    | When set to false, no activities will be logged regardless of the
    | other settings. Useful for local development or testing.
    |
    */
    'enabled' => env('SIMPLE_ACTIVITY_LOG_ENABLED', true),

    /*
     |--------------------------------------------------------------------------
     | Registered Models
     |--------------------------------------------------------------------------
     |
     | The models that will be automatically observed for activity logging.
     | Add the fully qualified class names of the models you want to track.
     |
     */
    'registered' => [
        //\App\Models\User::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Logger Class
    |--------------------------------------------------------------------------
    |
    | The logger class that will be used to observe model events and log
    | activities. You can extend the BaseModelLogger and create your own
    | custom logger by setting this value to your custom class.
    |
    */
    'logger' => ModelLogger::class,

    /*
    |--------------------------------------------------------------------------
    | Default Log Name
    |--------------------------------------------------------------------------
    |
    | This is the default log name used when logging activities. This can
    | be useful for filtering activities later.
    |
    */
    'default_log_name' => 'model',

    /*
     * The attribute of the user model to use for causer identification
     */

    /*
    |--------------------------------------------------------------------------
    | Causer Identifier
    |--------------------------------------------------------------------------
    |
    | The attribute of the user model to use for causer identification
    |
    */
    'causer_identifier' => 'email',

    /*
    |--------------------------------------------------------------------------
    | Log Only Specific Actions
    |--------------------------------------------------------------------------
    |
    | If you want to log only specific actions, you can specify them here.
    | Leave empty to log all actions. Available actions are defined in
    | the ObserverActionsEnum.
    |
    */
    'events' => [
        // Model-specific events
        // \App\Models\User::class => [
        // ObserverActionsEnum::CREATED->value,
        //ObserverActionsEnum::UPDATED->value,
        //  ],
    ],
];

