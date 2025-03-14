<?php

namespace App\Http\Controllers;

use App\Models\Sprint;
use App\Services\SprintService;

class AjaxController_old
{
    public function chartData($project)
    {
        /** @var  $sprintService SprintService */
        $sprintService = app()->make(SprintService::class);
        return $sprintService->getChartData($project);

    }
    public function chartDataSprint($project, $sprint)
    {
        /** @var  $sprintService SprintService */
        $sprintService = app()->make(SprintService::class);
        return $sprintService->getChartData($project, $sprint);

    }
}
