<?php

namespace App\Http\Controllers;

use App\Models\Sprint;
use App\Services\SprintService;

class AjaxController
{
    public function chartData($project)
    {
        /** @var  $sprintService SprintService */
        $sprintService = app()->make(SprintService::class);

        $data['chart'] = $sprintService->getChartData($project);
        $data['summaryData'] = view("summary", $sprintService->getSummaryFigures($project))->render();
        return $data;
    }

    public function chartDataSprint($project, $sprint)
    {
        /** @var  $sprintService SprintService */
        $sprintService = app()->make(SprintService::class);
        return $sprintService->getChartData($project, $sprint);
    }
}
