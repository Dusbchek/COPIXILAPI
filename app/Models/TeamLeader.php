<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamLeader extends Model
{

public function teamLeaders()
{
    return $this->belongsToMany(User::class, 'team_leaders');
}

}
