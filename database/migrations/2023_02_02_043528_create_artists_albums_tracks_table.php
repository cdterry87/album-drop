<?php

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
        Schema::create('artists_albums_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained('artists')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('album_id')->constrained('artists_albums')->onDelete('cascade')->onUpdate('cascade');
            $table->string('spotify_track_id');
            $table->string('name');
            $table->string('track_number');
            $table->string('duration_ms');
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
        Schema::dropIfExists('artists_albums_tracks');
    }
};
