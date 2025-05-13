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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            $table->string('short_name')->nullable()->after('last_name');
            $table->string('mobile_no')->after('short_name');
            $table->foreignId('org_id')->after('email')->constrained('organizations')->onDelete('cascade');
            $table->foreignId('role_id')->after('org_id')->constrained('roles')->onDelete('cascade');

            $table->string('designation')->nullable()->after('role_id');
            $table->boolean('is_active')->default(true)->after('designation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
