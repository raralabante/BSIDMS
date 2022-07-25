<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DraftingMaster;
use App\Models\JobDraftingStatus;
use Illuminate\Support\Facades\Auth;
use App\Models\Timesheet;
use App\Events\Message;
use App\Models\Activity;
use App\Models\RoleActivity;

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
        $new_activity = Activity::create([
            'user_id' => '0',
            'description' => '(SYSTEM) All jobs has been deactivated. Kindly turn it on manually if needed.',
            'status' => '0',
            'created_at' => now(),
        ]);

        RoleActivity::create([
            'activity_id' => $new_activity->id,
            'created_at' => now(),
        ]);
        event(new Message(''));

    }
}
