<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Issue extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'subject',
        'detail',
        'status_id',
        'client_id',
        'manager_id',
        'updated_at',
    ];

    public function isAttached(): bool
    {
        return $this->manager_id !== null;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

}
