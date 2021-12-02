<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'description',
    ];

    public function isOpened()
    {
        return $this->id === 1;
    }
    public function isWaitForClientAnswer()
    {
        return $this->id === 2;
    }
    public function isWaitForManagerAnswer()
    {
        return $this->id === 3;
    }
    public function isClosed()
    {
        return $this->id === 4;
    }

}
