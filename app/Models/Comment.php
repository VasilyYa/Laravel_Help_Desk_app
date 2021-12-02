<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'text',
        'author_id',
        'issue_id',
    ];

    public function getAuthorFullNameAttribute()
    {
        $author = User::find($this->author_id);
        return $author->name . ' ' . $author->last_name;
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }
}
