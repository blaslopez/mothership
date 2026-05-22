<?php

namespace Modules\Cockpit\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // --- Relations ---

    public function preferences(): HasMany
    {
        return $this->hasMany(UserPreference::class);
    }

    // --- Preference helpers ---

    public function preference(string $module, string $key, mixed $default = null): mixed
    {
        $pref = $this->preferences
            ->where('module', $module)
            ->where('key', $key)
            ->first();

        return $pref?->value ?? $default;
    }

    public function setPreference(string $module, string $key, mixed $value): void
    {
        $this->preferences()->updateOrCreate(
            ['module' => $module, 'key' => $key],
            ['value'  => $value]
        );
    }

    public function modulePreferences(string $module): array
    {
        return $this->preferences
            ->where('module', $module)
            ->pluck('value', 'key')
            ->toArray();
    }
}
