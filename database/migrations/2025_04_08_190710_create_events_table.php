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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->date('date_of_event');
            $table->string('address');
            $table->string('address_location')->nullable();
            $table->boolean('is_adv_paid')->default(false);
            $table->decimal('adv_amt',10,2)->nullable();
            $table->boolean('is_ret_paid')->default(false);
            $table->decimal('ret_amt',10,2)->nullable();
            $table->json('misc_spend')->nullable();

            $table->foreignId('org_id')->constrained('organizations')->onDelete('cascade');
            $table->foreignId('added_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
