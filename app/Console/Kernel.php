<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\DraftingMaster;
use App\Models\ShiftingSchedule;
use Illuminate\Support\Facades\Auth;
use App\Models\Timesheet;
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected $commands = [
        'App\Console\Commands\AutoOff'
    ];

    protected function schedule(Schedule $schedule)
    {
        $shifting_schedule = ShiftingSchedule::select(ShiftingSchedule::raw('DATE_FORMAT(morning_end, "%H:%i") as morning_end')
        ,ShiftingSchedule::raw('DATE_FORMAT(afternoon_end, "%H:%i") as afternoon_end'))
        ->where('id','=','1')->first();
        $schedule->command('auto:off')->dailyAt($shifting_schedule->morning_end);
        $schedule->command('auto:off')->dailyAt($shifting_schedule->afternoon_end);
        $schedule->command('auto:off')->dailyAt('23:59');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected function scheduleTimezone()
    {
        return 'Asia/Singapore';
    }

}
