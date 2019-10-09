<?php

namespace App\Http\Controllers;

use App\Models\DailyJiraTicket;
use App\Models\Project;
use App\Models\Sprint;
use App\Services\SprintService;

class AjaxController
{

    public function chartData($project, $dude = '')
    {

        /** @var  $sprintService SprintService */
        $sprintService = app()->make(SprintService::class);

        $data['chart'] = $sprintService->getChartData($project, '', $dude);
        $data['summaryData'] = view("summary", $sprintService->getSummaryFigures($project, '', $dude))->render();
        return $data;
    }

    public function chartDataSprint($project, $sprint)
    {
        /** @var  $sprintService SprintService */
        $sprintService = app()->make(SprintService::class);
        return $sprintService->getChartData($project, $sprint);
    }

    public function userList($project)
    {

        $op = "<nobr> &nbsp; <a class='' href=\"JavaScript:selectDude('')\">All</a> &nbsp; ";
        foreach (DailyJiraTicket::where('project_id', Project::where('project_key', $project)->first()->id)
                     ->whereNotNull('assigned_to')
                     ->select('assigned_to')
                     ->groupBy('assigned_to')
                     ->orderBy('assigned_to')
                     ->pluck('assigned_to')
                 as $dude
        ) {
            $dude = ($dude == null) ? 'Unassigned' : $dude;
            $op .= " &nbsp; <a  class='' href=\"JavaScript:selectDude('{$dude}')\">{$dude}</a> &nbsp; ";
        }
        $op .="</nobr>";
        return $op;
    }
}
