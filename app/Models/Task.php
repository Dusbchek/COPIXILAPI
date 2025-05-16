<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    
    protected $table = 'tasks';

  
    protected $fillable = [
        'title',
        'description',
        'status_id',
        'due_date',
        'user_id',
    ];

   
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'task_team');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(TaskStatus::class);
    }
}