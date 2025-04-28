<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('evaluation_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code'); // e.g., 'bahagian_i', 'bahagian_ii'
            $table->integer('weightage')->nullable();
            $table->boolean('is_pyd_section')->default(false);
            $table->boolean('is_ppp_section')->default(false);
            $table->boolean('is_ppk_section')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_sections');
    }
};
