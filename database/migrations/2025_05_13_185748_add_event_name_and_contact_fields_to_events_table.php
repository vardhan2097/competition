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
        Schema::table('events', function (Blueprint $table) {
            $table->string('event_name')->after('date_of_event')->nullable();
            $table->string('contact_person_name')->after('updated_by')->nullable();
            $table->string('contact_person_phone')->after('contact_person_name')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['event_name', 'contact_person_name', 'contact_person_phone']);
        });
    }
};
