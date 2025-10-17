<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'favorite_genres')) {
                $table->string('favorite_genres')->nullable()->after('bio');
            }
            if (!Schema::hasColumn('users', 'favorite_artists')) {
                $table->string('favorite_artists')->nullable()->after('favorite_genres');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'favorite_artists')) {
                $table->dropColumn('favorite_artists');
            }
            if (Schema::hasColumn('users', 'favorite_genres')) {
                $table->dropColumn('favorite_genres');
            }
        });
    }
};