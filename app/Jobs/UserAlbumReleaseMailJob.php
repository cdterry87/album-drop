<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\ArtistAlbum;
use Illuminate\Bus\Queueable;
use App\Models\UserAlbumRelease;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendUserAlbumReleaseMail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class UserAlbumReleaseMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Specify the number of days to look back for album releases
    public $days = 30;

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
        $albumsReleasedThisWeek = ArtistAlbum::query()
            ->with('artist')
            ->where('release_date', '>=', now()->subDays($this->days))
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
                ->pluck('id');

            // Check if any of the albums released this week are by one of the user's tracked artists and not in the album release mail log
            $usersAlbumsReleasedThisWeek = $albumsReleasedThisWeek
                ->whereIn('artist_id', $userArtistsIds)
                ->whereNotIn('id', $user->albumReleases->pluck('album_id'));

            // If the user has albums released this week by any of their tracked artists, send an email
            if ($usersAlbumsReleasedThisWeek->count() > 0) {
                Mail::to($user->email)->send(new SendUserAlbumReleaseMail($user, $usersAlbumsReleasedThisWeek));

                // Log that this user was sent an email with these albums so they don't get sent again
                UserAlbumRelease::query()->insert(
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
