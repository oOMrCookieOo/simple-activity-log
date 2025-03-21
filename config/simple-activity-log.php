<?php

// config for Mrcookie/SimpleActivityLog
use Mrcookie\SimpleActivityLog\Loggers\ModelLogger;

return [

    "enabled" => true,
    "logger" => ModelLogger::class,
    'log_name' => 'model_log',
    'causer_identifier' => 'email',
    "registered" => [
        \App\Models\MealPool::class
    ],

];
