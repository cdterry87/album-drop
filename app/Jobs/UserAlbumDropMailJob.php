<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\ArtistAlbum;
use App\Models\UserAlbumDrop;
use Illuminate\Bus\Queueable;
use App\Mail\SendUserAlbumDropMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class UserAlbumDropMailJob implements ShouldQueue
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
        // Get only albums released in the time period specified
        $albumsReleasedRecently = ArtistAlbum::query()
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

            // Check if any of the albums released recently are by one of the user's tracked artists and not in the album release mail log
            $usersAlbumsReleasedRecently = $albumsReleasedRecently
                ->whereIn('artist_id', $userArtistsIds)
                ->whereNotIn('id', $user->albumDrops->pluck('album_id'));

            // If the user has albums released this week by any of their tracked artists, send an email
            if ($usersAlbumsReleasedRecently->count() > 0) {
                Mail::to($user->email)->send(new SendUserAlbumDropMail($user, $usersAlbumsReleasedRecently));

                // Log that this user was sent an email with these albums so they don't get sent again
                UserAlbumDrop::query()->insert(
                    $usersAlbumsReleasedRecently->map(function ($album) use ($user) {
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
