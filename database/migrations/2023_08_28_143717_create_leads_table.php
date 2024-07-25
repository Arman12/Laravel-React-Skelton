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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('date_of_birth', 14)->nullable()->comment('YYYY-MM-DD');
            $table->enum('title', ['Mr','Mrs','Miss','Dr','Prof','Rev'])->nullable()->comment('\'Mr\', \'Mrs\', \'Miss\', \'Dr\', \'Prof\', \'Rev\'');
            $table->text('signature_src')->nullable();
            $table->tinyInteger('agree_terms')->default(0);
            $table->string('tax_year', 255)->nullable();
            $table->enum('tax_payer', ['yes','no'])->nullable()->comment('\'yes\',\'no\'');
            $table->tinyInteger('status')->default(0)->comment('0=partial, 1=completed, 2=doc generated, 3=sent email, 4=submitted');
            $table->text('comments')->nullable();
            $table->string('ip_address', 20)->nullable();
            $table->string('device', 100)->nullable();
            $table->string('browser', 100)->nullable();
            $table->string('document_url', 255)->nullable();
            $table->string('audit_pdf_url', 255)->nullable();
            $table->string('eml_file_url', 255)->nullable();
            $table->dateTime('document_date_time')->nullable();
            $table->dateTime('signature_date_time')->nullable();
            $table->bigIncrements('counter')->default(0);
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
