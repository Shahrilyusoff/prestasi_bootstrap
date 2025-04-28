<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('user_type_id')->nullable()->constrained('user_types');
            $table->foreignId('pyd_group_id')->nullable()->constrained('pyd_groups');
            $table->string('position')->nullable();
            $table->string('grade')->nullable();
            $table->string('ministry_department')->nullable();
            $table->string('ic_number')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['user_type_id']);
            $table->dropForeign(['pyd_group_id']);
            $table->dropColumn(['user_type_id', 'pyd_group_id', 'position', 'grade', 'ministry_department', 'ic_number']);
        });
    }
};
