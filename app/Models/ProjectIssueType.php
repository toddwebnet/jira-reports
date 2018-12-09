<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectIssueType extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'icon_url',
        'sub_task',
        'fields',
    ];
}