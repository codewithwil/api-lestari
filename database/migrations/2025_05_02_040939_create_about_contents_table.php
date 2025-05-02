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
        if(!Schema::hasTable('about_contents')) {
            Schema::create('about_contents', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->string('aboutConId', 15)->primary();
                $table->string('about_id', 15);
                $table->string('title', 50);
                $table->text('desc');
                $table->timestamps();

                $table->foreign('about_id')
                ->references('aboutId')
                ->on('abouts')
                ->onUpdate("cascade")
                ->onDelete("restrict");
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_contents');
    }
};
