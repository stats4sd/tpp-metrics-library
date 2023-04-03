<?php

namespace App\Models;

use App\Models\Import;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use HasFactory, Notifiable, CrudTrait, HasRoles;


    protected $guarded = [];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin(): bool
    {
        return true;
    }

    public function canAccessFilament(): bool
    {
        return true;
    }

    public function canImpersonate(): bool
    {
        return $this->isAdmin();
    }


    public function discussionPoints(): HasMany
    {
        return $this->hasMany(DiscussionPoint::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function feedbackResolved(): HasMany
    {
        return $this->hasMany(Feedback::class, 'resolver_id');
    }

    public function imports(): HasMany
    {
        return $this->hasMany(Import::class);
    }

}
