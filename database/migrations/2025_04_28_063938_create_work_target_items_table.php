<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('work_target_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_target_id')->constrained('work_targets');
            $table->text('activity');
            $table->text('performance_indicator');
            $table->boolean('is_added')->default(false);
            $table->boolean('is_removed')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_target_items');
    }
};
