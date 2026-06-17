<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'job_id',
        'status',
        'text',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}