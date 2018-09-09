<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

            if ($sprint == null) {
                // will be caught in the catch
                throw new \Exception('');
            }

            return date("Y-m-d", strtotime($sprint->begin_date));
        } catch (\Exception $e) {
            return $today;
        }
    }

    public static function getCurrentSprint()
    {
        $today = date("Y-m-d", time());
        try {
            return $sprint = Sprint::where('begin_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->orderBy('begin_date')
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

}
