<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyJiraTicket extends Model
{
    protected $fillable = [
        'project_id',
        'sprint_id',
        'collection_date',
        'ticket_number',
        'issue_type',
        'status',
        'assigned_to',
        'priority',
        'points',
    ];

}
