<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProjectImportLog extends Model
{
    protected $fillable = ['source_key', 'source_id', 'source'];
}