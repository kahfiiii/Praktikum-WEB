<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Comment extends Model
{
    protected $fillable = [
        'material_id', 'user_id', 'parent_id', 'body', 'status', 'reports',
        'kind', 'upvotes', 'source_ref', 'is_verified', 'verified_by',
    ];

    protected $casts = ['is_verified' => 'boolean'];

    public function votes(): HasMany
    {
        return $this->hasMany(CommentVote::class);
    }

    public function reportsRelation(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function isQuestion(): bool
    {
        return $this->kind === 'question';
    }

    public function answerState(): string
    {
        if ($this->is_verified) {
            return 'Terverifikasi';
        }

        return $this->replies()->count() > 0 ? 'Terjawab' : 'Belum terjawab';
    }

    public function answerStateClasses(): string
    {
        if ($this->is_verified) {
            return 'border-lime-dark bg-lime-soft text-lime-ink';
        }

        return $this->replies()->count() > 0
            ? 'border-primary bg-primary-soft text-primary'
            : 'border-line-soft bg-gray-fill text-ink-muted';
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->oldest();
    }
}
