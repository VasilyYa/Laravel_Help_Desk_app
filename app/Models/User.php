<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_name',
        'phone',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->last_name;
    }

    public function issues()
    {
        return $this->hasMany(Issue::class,'manager_id','id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class,'author_id','id');
    }

    public function isClient(): bool
    {
        return (int)$this->role_id === 1;
    }
    public function isManager(): bool
    {
        return (int)$this->role_id === 2;
    }
    public function isSeniorManager(): bool
    {
        return (int)$this->role_id === 3;
    }
    public function isAdmin(): bool
    {
        return (int)$this->role_id === 4;
    }

    public function isClientOfIssue(Issue $issue): bool
    {
        return $this->id == $issue->client_id;
    }
    public function isManagerOfIssue(Issue $issue): bool
    {
        return $this->id == $issue->manager_id;
    }

    public function isAuthorOfComment(Comment $comment): bool
    {
        return $this->id == $comment->author_id;
    }
}
