<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    // 1. Allow mass assignment for all these fields
    protected $fillable = [
        'title', 
        'description', 
        'category_id', 
        'employer_id', 
        'responsibilities', 
        'user_id',
        'benefits', 
        'work_type', 
        'location', 
        'salary_range', 
        'application_deadline', 
        'status'
    ];

    // 2. Relationship: A job belongs to one employer (User)
    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    // 3. Relationship: A job belongs to one category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 4. Relationship: A job can require many technologies
    public function technologies()
    {
        return $this->belongsToMany(Technology::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}