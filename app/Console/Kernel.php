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
        if (app()->environment('local')) {
            // Local testing (sail artisan schedule:work)
            $schedule->job(new ArtistAlbumsJob())->everyMinute();
            $schedule->job(new ArtistRelatedArtistJob())->everyMinute();
            $schedule->job(new UserAlbumDropMailJob())->everyMinute();
        } else {
            // Get artist albums
            $schedule->job(new ArtistAlbumsJob())->twiceDailyAt('08:00', '20:00');

            // Get related artists
            $schedule->job(new ArtistRelatedArtistJob())->twiceDailyAt('12:00', '00:00');

            // Send new album release email once per week
            $schedule->job(new UserAlbumDropMailJob())->weeklyOn(5, '10:00');
        }
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
