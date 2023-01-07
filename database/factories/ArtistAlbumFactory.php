<?php

namespace Database\Factories;

use App\Models\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArtistAlbum>
 */
class ArtistAlbumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $artist = Artist::factory()->create();

        return [
            'artist_id' => $artist->id,
            'name' => $this->faker->sentence,
            'image' => $this->faker->imageUrl,
            'url' => $this->faker->url,
            'release_date' => $this->faker->date,
            'spotify_album_id' => $this->faker->uuid,
            'type' => 'album',
        ];
    }
}
