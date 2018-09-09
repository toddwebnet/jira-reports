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
        return $sprintService->getChartData($project);

    }
}
