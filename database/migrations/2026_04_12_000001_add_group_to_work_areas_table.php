<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_areas', function (Blueprint $table) {
            $table->string('group')->default('talleres-productivos')->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('work_areas', function (Blueprint $table) {
            $table->dropColumn('group');
        });
    }
};
