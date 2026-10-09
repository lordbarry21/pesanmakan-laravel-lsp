<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
class BackupOrdersCommand extends Command {
    protected $signature = 'orders:backup';
    public function handle(): int { return 0; }
}
