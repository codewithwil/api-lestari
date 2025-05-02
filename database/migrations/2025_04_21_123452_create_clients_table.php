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
        if(!Schema::hasTable('clients')) {
            Schema::create('clients', function (Blueprint $table){
                $table->engine = "InnoDB";   
                $table->string('clientId', 15)->primary();
                $table->string('image', 200);
                $table->string('name', 75);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
