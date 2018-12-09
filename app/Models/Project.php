<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['jira_id', 'project_key', 'project_name'];

    public function issueTypes(){
        return $this->hasMany(ProjectIssueType::class);
    }
}
