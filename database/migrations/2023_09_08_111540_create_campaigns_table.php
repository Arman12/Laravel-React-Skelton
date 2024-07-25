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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->string('name')->nullable();
            $table->integer('sms_template_id');
            $table->integer('email_template_id');
            $table->integer('datafrom')->default(1);
            $table->integer('days')->nullable();
            $table->string('recursion')->nullable();
            $table->string('start_time');
            $table->string('type');
            $table->string('end_time');
            $table->integer('hours')->nullable();
            $table->integer('iterations')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
