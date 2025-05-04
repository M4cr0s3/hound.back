<?php

namespace App\Models;

use App\Core\Filter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

final class User extends Authenticatable implements JWTSubject
{
    use Filterable, HasFactory, Notifiable, Searchable;

    protected $fillable = [
        'email',
        'role_id',
        'name',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(
            Team::class,
            'team_members',
            'user_id',
            'team_id'
        );
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(RefreshToken::class);
    }

    public function assignments(): MorphMany
    {
        return $this->morphMany(IssueAssignment::class, 'assignable');
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invite::class);
    }

    public function getJWTIdentifier(): int
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function toSearchableArray(): array
    {
        return ['id' => (string) $this->id, 'name' => $this->name, 'email' => $this->email];
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
