<?php

namespace App\Console;

use App\Jobs\ArtistAlbumsJob;
use App\Jobs\UserAlbumDropMailJob;
use App\Jobs\ArtistRelatedArtistJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $timezone = 'America/Chicago';

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
            $schedule->job(new ArtistAlbumsJob())->hourlyAt(30)
                ->timezone($this->timezone);

            // Get related artists
            $schedule->job(new ArtistRelatedArtistJob())->hourlyAt(45)
                ->timezone($this->timezone);

            // Send new album release emails
            $schedule->job(new UserAlbumDropMailJob())->weeklyOn(6, '3:00')
                ->timezone($this->timezone);
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
