<?php

namespace App\Models;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
    ];
    public function members()
    {
        return $this->belongsToMany(User::class);
    }
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
