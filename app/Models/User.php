<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

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

}
