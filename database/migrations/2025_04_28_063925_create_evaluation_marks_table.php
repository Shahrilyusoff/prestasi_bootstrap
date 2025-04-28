<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('evaluation_marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('evaluations');
            $table->foreignId('criteria_id')->constrained('evaluation_criterias');
            $table->integer('ppp_mark')->nullable();
            $table->integer('ppk_mark')->nullable();
            $table->text('ppp_comment')->nullable();
            $table->text('ppk_comment')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_marks');
    }
};
