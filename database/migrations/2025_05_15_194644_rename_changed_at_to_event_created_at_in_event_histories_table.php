<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('event_histories', function (Blueprint $table) {
            $table->renameColumn('changed_at', 'event_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_histories', function (Blueprint $table) {
            $table->renameColumn('event_created_at', 'changed_at');
        });
    }
};
