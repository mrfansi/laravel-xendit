<?php

namespace Mrfansi\XenditSdk\Commands;

use Illuminate\Console\Command;

class XenditSdkCommand extends Command
{
    public $signature = 'xendit-sdk';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
