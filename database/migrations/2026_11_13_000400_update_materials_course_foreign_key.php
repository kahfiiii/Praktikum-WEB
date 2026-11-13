<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Untuk MySQL / database yang mendukung foreign key drop & alter
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            Schema::table('materials', function (Blueprint $table) {
                $table->dropForeign(['course_id']);
                $table->foreign('course_id')->references('id')->on('courses')->restrictOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            Schema::table('materials', function (Blueprint $table) {
                $table->dropForeign(['course_id']);
                $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
            });
        }
    }
};
