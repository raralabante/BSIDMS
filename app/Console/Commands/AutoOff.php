<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DraftingMaster;
use App\Models\JobDraftingStatus;
use Illuminate\Support\Facades\Auth;
use App\Models\Timesheet;
use App\Events\Message;
class AutoOff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:off';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto inactive jobs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        JobDraftingStatus::query()->update(['status' => 0]);
        Timesheet::whereNull('job_stop')->update(['job_stop' => now()]);
        event(new Message(''));
        

    }
}
