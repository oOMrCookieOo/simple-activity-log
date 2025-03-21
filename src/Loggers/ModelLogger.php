<?php

namespace Mrcookie\SimpleActivityLog\Loggers;

class ModelLogger extends BaseModelLogger
{
    protected function getLogName(): string
    {
        return config('simple-activity-log.log_name');
    }
}
