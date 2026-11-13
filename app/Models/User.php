<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nim',
        'initials',
        'faculty',
        'study_program',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isModerator(): bool
    {
        return in_array($this->role, ['moderator', 'admin'], true);
    }

    public function canModerateContent(?string $contentFaculty): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isModerator()) {
            return !empty($this->faculty) && $this->faculty === $contentFaculty;
        }

        return false;
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            'admin' => 'Admin',
            'moderator' => 'Moderator',
            default => 'Mahasiswa',
        };
    }

    public function roleClasses(): string
    {
        return match ($this->role) {
            'admin' => 'border-violet bg-violet-soft text-violet',
            'moderator' => 'border-primary bg-primary-soft text-primary',
            default => 'border-line-soft bg-gray-fill text-ink-muted',
        };
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function initials(): string
    {
        $raw = $this->attributes['initials'] ?? null;
        if (!empty($raw)) {
            return $raw;
        }

        $letters = '';
        foreach (preg_split('/\s+/', trim((string) $this->name)) as $part) {
            if ($part !== '') {
                $letters .= mb_strtoupper(mb_substr($part, 0, 1));
            }
            if (mb_strlen($letters) === 2) {
                break;
            }
        }

        return $letters ?: 'ST';
    }
}
