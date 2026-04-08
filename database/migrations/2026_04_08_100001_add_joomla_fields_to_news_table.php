<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->unsignedBigInteger('joomla_id')->nullable()->unique()->after('id');
            $table->string('language', 10)->default('es')->after('is_featured');
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropUnique(['joomla_id']);
            $table->dropColumn(['joomla_id', 'language']);
        });
    }
};
