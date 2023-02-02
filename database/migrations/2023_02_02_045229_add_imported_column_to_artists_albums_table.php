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
        Schema::table('artists_albums', function (Blueprint $table) {
            $table->boolean('imported')
                ->after('type')
                ->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('artists_albums', function (Blueprint $table) {
            $table->dropColumn(array_merge([
                'imported',
            ]));
        });
    }
};
