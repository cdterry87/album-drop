<?php

namespace App\Jobs;

use App\Mail\SendNewAlbumReleaseMail;
use App\Models\User;
use App\Models\Album;
use App\Models\AlbumReleaseMailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendNewAlbumReleaseMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get only albums released in the past week
        $albumsReleasedThisWeek = Album::query()
            ->with('artist')
            ->where('release_date', '>=', now()->subDays(7))
            ->where('release_date', '<=', now())
            ->orderBy('release_date', 'asc')
            ->get();

        // Get all subscribed users
        $users = User::query()
            ->with('artists')
            ->where('subscribed', true)
            ->get();

        foreach ($users as $user) {
            // Get a list of artist ids for this user
            $userArtistsIds = $user
                ->artists
                ->pluck('spotify_artist_id');

            // Check if any of the albums released this week are by one of the user's tracked artists and not in the album release mail log
            $usersAlbumsReleasedThisWeek = $albumsReleasedThisWeek
                ->whereIn('spotify_artist_id', $userArtistsIds)
                ->whereNotIn('id', $user->albumReleaseMailLogs->pluck('album_id'));

            // If the user has albums released this week by any of their tracked artists, send an email
            if ($usersAlbumsReleasedThisWeek->count() > 0) {
                Mail::to($user->email)->send(new SendNewAlbumReleaseMail($user, $usersAlbumsReleasedThisWeek));

                // Log that this user was sent an email with these albums so they don't get sent again
                AlbumReleaseMailLog::query()->insert(
                    $usersAlbumsReleasedThisWeek->map(function ($album) use ($user) {
                        return [
                            'album_id' => $album->id,
                            'user_id' => $user->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    })->toArray()
                );
            }
        }
    }
}