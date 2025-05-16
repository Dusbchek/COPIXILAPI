<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\User;

class Team extends Model
{
  
    protected $fillable = [
        'name',
    ];

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_team');
    }

 
public function members()
{
    return $this->belongsToMany(User::class, 'team_members');
}

public function leader()
{
    return $this->belongsTo(User::class, 'user_id');
}
    public function teamLeaders()
    {
        return $this->belongsToMany(User::class, 'team_leaders', 'team_id', 'user_id');
    }
}
