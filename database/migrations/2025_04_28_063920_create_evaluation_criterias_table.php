<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('evaluation_criterias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('evaluation_sections');
            $table->text('criteria');
            $table->integer('max_mark');
            $table->integer('weightage')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_criterias');
    }
};
