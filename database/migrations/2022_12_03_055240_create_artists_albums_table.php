<?php

/**
 * Albums for artists pulled from Spotify API when users track them.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artists_albums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained('artists')->onDelete('cascade')->onUpdate('cascade');
            $table->string('spotify_album_id');
            $table->string('name');
            $table->string('image')->nullable();
            $table->date('release_date');
            $table->string('url');
            $table->string('type');
            $table->boolean('imported')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('albums');
    }
};
