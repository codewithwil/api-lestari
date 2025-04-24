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
        Schema::create('testimonial_contents', function (Blueprint $table) {
            $table->string('testimonialConId', 15)->primary();
            $table->string('testimonial_id', 15);
            $table->string('image', 200);
            $table->string('name', 75);
            $table->text('desc');
            $table->timestamps();

            $table->foreign('testimonial_id')
            ->references('testimonialId')
            ->on('testimonials')
            ->onUpdate("cascade")
            ->onDelete("restrict");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonial_contents');
    }
};
