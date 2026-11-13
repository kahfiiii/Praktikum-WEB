<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Material extends Model
{
    protected $fillable = [
        'course_id', 'user_id', 'title', 'file_type', 'file_size_kb', 'file_path', 'original_filename', 'body', 'status',
        'verification_status', 'verified_by', 'verified_at', 'rejection_reason', 'faculty',
    ];

    protected $casts = ['verified_at' => 'datetime'];

    protected static function booted(): void
    {
        static::deleting(function (Material $material) {
            if ($material->file_path) {
                if (\Illuminate\Support\Facades\Storage::disk('local')->exists($material->file_path)) {
                    \Illuminate\Support\Facades\Storage::disk('local')->delete($material->file_path);
                }
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($material->file_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($material->file_path);
                }
            }
        });
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function verificationLabel(): string
    {
        return match ($this->verification_status) {
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            default => 'Belum diverifikasi',
        };
    }

    public function verificationClasses(): string
    {
        return match ($this->verification_status) {
            'verified' => 'border-lime-dark bg-lime-soft text-lime-ink',
            'rejected' => 'border-coral bg-coral-soft text-coral-ink',
            default => 'border-line-soft bg-gray-fill text-ink-muted',
        };
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->latest();
    }

    public function sizeLabel(): string
    {
        return $this->file_size_kb >= 1024
            ? round($this->file_size_kb / 1024, 1).' MB'
            : $this->file_size_kb.' KB';
    }

    public function typeClasses(): string
    {
        return match (strtoupper($this->file_type)) {
            'PPTX' => 'border-lime-dark bg-lime-soft text-lime-ink',
            'PDF' => 'border-primary bg-primary-soft text-primary',
            default => 'border-line-soft bg-gray-fill text-ink-muted',
        };
    }
}
