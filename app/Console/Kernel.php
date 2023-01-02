<?php

namespace App\Console;

use App\Jobs\ArtistAlbumsJob;
use App\Jobs\UserAlbumDropMailJob;
use App\Jobs\ArtistRelatedArtistJob;
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
        // Get artist albums
        $schedule->job(new ArtistAlbumsJob())->everyOddHour();

        // Get related artists
        $schedule->job(new ArtistRelatedArtistJob())->everyTwoHours();

        // Send new album release email once per week
        $schedule->job(new UserAlbumDropMailJob())->weeklyOn(1, '11:00');

        /**
         * Local testing
         * Run: sail artisan schedule:work
         */
        $schedule->job(new ArtistAlbumsJob())->everyMinute();
        $schedule->job(new ArtistRelatedArtistJob())->everyMinute();
        $schedule->job(new UserAlbumDropMailJob())->everyMinute();
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
