<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectAvatar extends Model
{
    protected $fillable = [
        'project_id',
        'size',
        'url',
    ];
}