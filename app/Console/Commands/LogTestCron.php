<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LogTestCron extends Command
{
    protected $signature = 'test:log-cron';
    protected $description = 'Ghi log thử để kiểm tra cron job';

    public function handle()
    {
        Log::info('Cron Job đã chạy lúc: ' . now());
        $this->info('Đã ghi log thành công.');
    }
}
