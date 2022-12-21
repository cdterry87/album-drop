<?php

namespace App\Console;

use App\Jobs\GetArtistAlbumsJob;
use App\Jobs\SendNewAlbumReleaseMailJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Get artist albums on Friday at 3am
        // $schedule->job(new GetArtistAlbumsJob())->weeklyOn(5, '03:00');

        // Send new album release email on Sunday at 3am
        // $schedule->job(new SendNewAlbumReleaseEmailJob())->weeklyOn(7, '03:00');

        /**
         * Local testing
         * Run: sail artisan schedule:work
         */
        $schedule->job(new SendNewAlbumReleaseMailJob())->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
