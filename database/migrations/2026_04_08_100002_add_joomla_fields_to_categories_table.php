<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('joomla_id')->nullable()->unique()->after('id');
            $table->foreignId('parent_id')->nullable()->after('joomla_id')->constrained('categories')->nullOnDelete();
            $table->text('description')->nullable()->after('slug');
            $table->string('language', 10)->default('es')->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropUnique(['joomla_id']);
            $table->dropColumn(['joomla_id', 'parent_id', 'description', 'language']);
        });
    }
};
