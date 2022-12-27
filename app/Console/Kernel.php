<?php

namespace App\Console;

use App\Jobs\ArtistAlbumsJob;
use App\Jobs\ArtistRelatedArtistJob;
use App\Jobs\UserAlbumReleaseMailJob;
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
        // Get artist albums daily at 2am
        // $schedule->job(new ArtistAlbumsJob())->dailyAt('02:00');

        // Get related artists daily at 6am
        // $schedule->job(new ArtistRelatedArtistJob())->dailyAt('06:00');

        // Send new album release email on Sunday at 3am
        // $schedule->job(new UserAlbumReleaseMailJob())->weeklyOn(7, '03:00');

        /**
         * Local testing
         * Run: sail artisan schedule:work
         */
        // $schedule->job(new ArtistAlbumsJob())->everyMinute();
        // $schedule->job(new UserAlbumReleaseMailJob())->everyMinute();
        $schedule->job(new ArtistRelatedArtistJob())->everyMinute();
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
