<?php

namespace Mrcookie\SimpleActivityLog\Commands;

use Illuminate\Console\Command;

class SimpleActivityLogCommand extends Command
{
    public $signature = 'simple-activity-log';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
