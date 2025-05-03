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
        if(!Schema::hasTable('service_contents')) {
            Schema::create('service_contents', function (Blueprint $table){
                $table->engine = "InnoDB";   
                $table->string('serviceContentId', 15)->primary();
                $table->string('service_id', 15);
                $table->string('image', 200);
                $table->string('title', 75);
                $table->text('content');
                $table->string('linkIcon', 200);
                $table->string('linkTitle', 75);
                $table->string('link', 100);
                $table->string('linkBackground', 20);
                $table->string('linkColor', 20);
                $table->timestamps();

                $table->foreign('service_id')
                ->references('serviceId')
                ->on('services')
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
        Schema::dropIfExists('service_contents');
    }
};
