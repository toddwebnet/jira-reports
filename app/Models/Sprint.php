<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
    protected $fillable = [
        'sprint_name', 'begin_date', 'end_date'
    ];
}
