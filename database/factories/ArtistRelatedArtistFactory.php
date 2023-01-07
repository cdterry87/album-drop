<?php

namespace Database\Factories;

use App\Models\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArtistRelatedArtist>
 */
class ArtistRelatedArtistFactory extends Factory
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
            'spotify_artist_id' => $this->faker->uuid,
            'name' => $this->faker->words(3, true),
            'image' => $this->faker->imageUrl,
            'url' => $this->faker->url,

        ];
    }
}
