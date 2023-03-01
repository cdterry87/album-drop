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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('country', 2)->default('US');

            // Spotify Account Identifiers
            $table->string('spotify_id')->nullable();
            $table->string('spotify_token')->nullable();
            $table->string('spotify_refresh_token')->nullable();

            // Email Subscription Settings
            $table->boolean('subscribed')->default(false);
            $table->boolean('unsubscribe_token')->nullable();

            // Spotify Playlist Settings
            $table->boolean('create_mega_playlist')->default(false);
            $table->string('spotify_mega_playlist_id')->nullable();
            $table->boolean('create_new_releases_playlist')->default(false);
            $table->string('spotify_new_releases_playlist_id')->nullable();
            $table->boolean('create_weekly_playlist')->default(false);
            $table->string('spotify_weekly_playlist_id')->nullable();
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
        Schema::dropIfExists('users');
    }
};
