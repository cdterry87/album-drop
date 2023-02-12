<?php

namespace App\Console;

use App\Jobs\ArtistAlbumsJob;
use App\Jobs\ArtistAlbumTracksJob;
use App\Jobs\UserAlbumDropMailJob;
use App\Jobs\ArtistRelatedArtistJob;
use App\Jobs\UserMegaPlaylistManagerJob;
use App\Jobs\UserNewReleasesPlaylistManagerJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Get the timezone that should be used by default for scheduled tasks.
     *
     * @return \DateTimeZone|string|null
     */
    protected function scheduleTimezone()
    {
        return 'America/Chicago';
    }

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
            // $schedule->job(new UserMegaPlaylistManagerJob())->everyMinute();
            // $schedule->job(new ArtistAlbumsJob())->everyMinute();
            // $schedule->job(new ArtistAlbumTracksJob())->everyMinute();
            // $schedule->job(new ArtistRelatedArtistJob())->everyMinute();
            // $schedule->job(new UserAlbumDropMailJob())->everyMinute();
            $schedule->job(new UserNewReleasesPlaylistManagerJob())->everyMinute();
        } else {
            // Manage Users' Mega Playlists
            $schedule->job(new UserMegaPlaylistManagerJob())->everyFourHours();

            // Get artist albums
            $schedule->job(new ArtistAlbumsJob())->dailyAt('02:00');

            // Get artist album tracks
            $schedule->job(new ArtistAlbumTracksJob())->dailyAt('03:00');

            // Get related artists
            $schedule->job(new ArtistRelatedArtistJob())->dailyAt('04:00');

            // Send new album release emails
            $schedule->job(new UserAlbumDropMailJob())->weeklyOn(6, '5:00');

            // Manage Users' New Releases Playlists
            $schedule->job(new UserNewReleasesPlaylistManagerJob())->weeklyOn(6, '6:00');
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
