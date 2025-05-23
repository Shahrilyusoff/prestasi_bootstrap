<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('evaluation_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false);
            $table->enum('type', ['yearly', 'mid_year', 'adhoc']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_periods');
    }
};