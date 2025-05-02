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
        if(!Schema::hasTable('abouts')) {
            Schema::create('abouts', function (Blueprint $table){
                $table->engine = "InnoDB";   
                $table->string('aboutId', 15)->primary();
                $table->string('image', 200);
                $table->string('header', 75);
                $table->text('desc');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abouts');
    }
};
