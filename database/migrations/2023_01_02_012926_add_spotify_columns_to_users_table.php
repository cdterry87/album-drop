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
        Schema::table('users', function (Blueprint $table) {
            $table->string('spotify_id')
                ->after('password')
                ->nullable();

            $table->string('spotify_token')
                ->after('spotify_id')
                ->nullable();

            $table->string('spotify_refresh_token')
                ->after('spotify_token')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(array_merge([
                'spotify_id',
                'spotify_token',
                'spotify_refresh_token',
            ]));
        });
    }
};
