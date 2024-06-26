<?php

namespace App\Models;

use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'team_id'

    ];
    public function employees(){
        return $this->hasMany(Employee::class);
    }
    public function team(){
        return $this->belongsTo(Team::class);
    }
}
