<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Verifikasi materi oleh moderator
        Schema::table('materials', function (Blueprint $table) {
            $table->string('verification_status', 20)->default('unverified'); // unverified|verified|rejected
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('faculty', 80)->nullable();
        });

        // Fakultas pada mata kuliah
        Schema::table('courses', function (Blueprint $table) {
            $table->string('faculty', 80)->default('Fakultas Ilmu Komputer');
        });

        // Diskusi: pertanyaan, jawaban, upvote, verifikasi jawaban
        Schema::table('comments', function (Blueprint $table) {
            $table->string('kind', 20)->default('answer'); // question|answer
            $table->unsignedInteger('upvotes')->default(0);
            $table->text('source_ref')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
        });

        // Laporan konten
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('reportable_type', 30);
            $table->unsignedBigInteger('reportable_id');
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->string('reason', 40);
            $table->text('detail')->nullable();
            $table->string('status', 20)->default('baru'); // baru|diproses|selesai
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['reportable_type', 'reportable_id']);
        });

        // Upvote jawaban per pengguna
        Schema::create('comment_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['comment_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_votes');
        Schema::dropIfExists('reports');
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn(['kind', 'upvotes', 'source_ref', 'is_verified', 'verified_by']);
        });
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('faculty');
        });
        Schema::table('materials', function (Blueprint $table) {
            $table->dropConstrainedForeignId('verified_by');
            $table->dropColumn(['verification_status', 'verified_at', 'rejection_reason', 'faculty']);
        });
    }
};
