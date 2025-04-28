<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pyd_id')->constrained('users');
            $table->foreignId('ppp_id')->constrained('users');
            $table->foreignId('ppk_id')->constrained('users');
            $table->foreignId('pyd_group_id')->constrained('pyd_groups');
            $table->year('year');
            $table->enum('status', ['draf', 'pyd_submit', 'ppp_submit', 'ppk_submit', 'selesai'])->default('draf');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluations');
    }
};