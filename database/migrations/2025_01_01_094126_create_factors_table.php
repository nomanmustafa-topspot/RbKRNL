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
        Schema::create('factors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pdf_template_id');
            $table->string('factor');
            $table->string('type'); // e.g., web presence, SEO, etc.
            $table->text('value');
            $table->text('result');
            $table->timestamps();
            $table->foreign('pdf_template_id')->references('id')->on('pdf_templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factors');
    }
};
