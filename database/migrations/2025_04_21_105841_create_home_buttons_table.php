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
        Schema::create('home_buttons', function (Blueprint $table) {
            $table->string('homeButtonId')->primary();
            $table->string('home_id', 15);
            $table->string('icon', 200)->nullable(true);
            $table->string('text', 75);
            $table->string('link', 75);
            $table->string('background', 20);
            $table->string('color', 20);
            $table->timestamps();

            $table->foreign('home_id')
            ->references('homeId')
            ->on('homes')
            ->onUpdate("cascade")
            ->onDelete("restrict");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_buttons');
    }
};
