<?php

namespace App\Console\Commands;

use App\Models\Sprint;
use Illuminate\Console\Command;

class GenerateSprints extends Command
{
    protected $signature = 'generateSprints';

    public function handle()
    {
        $sprintName = '2019-09-30';
        $today = date("Y-m-d");
        while (strtotime($sprintName) < strtotime($today))
        {
            if(Sprint::where('sprint_name', $sprintName)->get()->count() == 0){
                $data = [
                    'sprint_name' => $sprintName,
                    'begin_date' => date("Y-m-d", strtotime($sprintName . ' - 2 days')),
                    'end_date' => date("Y-m-d", strtotime($sprintName . ' + 11 days'))
                ];
                Sprint::create($data);
                print ".";
            }
            $sprintName = date("Y-m-d", strtotime($sprintName . ' + 14 days'));
        }


    }
}
