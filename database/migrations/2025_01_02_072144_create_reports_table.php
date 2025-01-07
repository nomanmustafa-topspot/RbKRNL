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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('pdf_template_id');
            $table->string('file_path')->nullable();
            $table->string('score')->nullable();
            $table->string('website_image')->nullable();
            $table->timestamp('generated_at');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('pdf_template_id')->references('id')->on('pdf_templates')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
