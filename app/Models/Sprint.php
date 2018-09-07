<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
    protected $fillable = [
        'sprint_name', 'begin_date', 'end_date'
    ];

    public static function getCurrentSprintStartDate()
    {
        $today = date("Y-m-d", time());
        try {
            $sprint = Sprint::where('begin_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->orderBy('begin_date')
                ->firstOrFail();
            return date("Y-m-d", strtotime($sprint->begin_date));
        } catch (\Exception $e) {
            return $today;
        }
    }
}
