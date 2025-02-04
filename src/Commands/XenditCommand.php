<?php

namespace Mrfansi\LaravelXendit\Commands;

use Illuminate\Console\Command;

class XenditCommand extends Command
{
    public $signature = 'laravel-xendit';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
