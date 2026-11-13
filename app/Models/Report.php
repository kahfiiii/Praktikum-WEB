<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = ['reportable_type', 'reportable_id', 'reporter_id', 'reason', 'detail', 'status', 'handled_by'];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function reportable()
    {
        return $this->morphTo();
    }

    public function getTargetFaculty(): ?string
    {
        if ($this->reportable_type === 'material') {
            $material = $this->reportable ?: Material::find($this->reportable_id);
            return $material?->faculty ?? $material?->course?->faculty;
        }

        if ($this->reportable_type === 'comment') {
            $comment = $this->reportable ?: Comment::find($this->reportable_id);
            return $comment?->material?->faculty ?? $comment?->material?->course?->faculty;
        }

        return null;
    }

    public function reasonLabel(): string
    {
        return match ($this->reason) {
            'hak-cipta' => 'Melanggar hak cipta',
            'tidak-pantas' => 'Tidak pantas',
            'informasi-salah' => 'Informasi salah',
            default => 'Lainnya',
        };
    }

    public function statusClasses(): string
    {
        return match ($this->status) {
            'baru' => 'border-coral bg-coral-soft text-coral-ink',
            'diproses' => 'border-primary bg-primary-soft text-primary',
            default => 'border-lime-dark bg-lime-soft text-lime-ink',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'baru' => 'Baru',
            'diproses' => 'Diproses',
            default => 'Selesai',
        };
    }

    public function contentLabel(): string
    {
        return $this->reportable_type === 'material' ? 'Materi' : 'Komentar';
    }
}
