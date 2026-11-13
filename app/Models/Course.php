<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Course extends Model
{
    protected $fillable = ['name', 'code', 'slug', 'description', 'semester', 'accent', 'is_active', 'faculty'];

    protected $casts = ['is_active' => 'boolean'];

    protected static function booted(): void
    {
        static::saving(function (Course $course) {
            if (blank($course->slug)) {
                $course->slug = Str::slug($course->name);
            }
        });
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function accentClasses(): string
    {
        return match ($this->accent) {
            'coral' => 'bg-coral-soft text-coral-ink',
            'lime' => 'bg-lime-soft text-lime-ink',
            'violet' => 'bg-violet-soft text-violet',
            default => 'bg-primary-soft text-primary',
        };
    }
}
