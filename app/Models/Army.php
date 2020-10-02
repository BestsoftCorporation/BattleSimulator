<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Army extends Model
{
    protected $fillable = [
        'name',
        'units',
        'strategy',
        'gameID',
        'status'
    ];

    //use HasFactory;
}
