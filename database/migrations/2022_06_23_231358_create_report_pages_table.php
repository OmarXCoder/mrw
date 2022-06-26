<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_pages', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('heading')->nullable();
            $table->longText('content')->nullable();
            $table->json('meta')->nullable();
            $table->boolean('include_header')->default(true);
            $table->boolean('include_footer')->default(true);
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_pages');
    }
};
