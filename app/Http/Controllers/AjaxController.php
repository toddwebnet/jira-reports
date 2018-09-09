<?php

namespace App\Http\Controllers;

use App\Models\Sprint;
use App\Services\SprintService;

class AjaxController
{
    public function chartData()
    {
        /** @var  $sprintService SprintService */
        $sprintService = app()->make(SprintService::class);
        return $sprintService->getChartData('PE');

        return;
        $data = [
            'labels' => ['January', 'February'],
            'datasets' => [
                [
                    'label' => 'open',
                    'backgroundColor' => '#990000',
                    'data' => [
                        10, 20
                    ]
                ],

                [
                    'label' => 'closed',
                    'backgroundColor' => '#009900',
                    'data' => [
                        10, 20
                    ]
                ],

            ],

        ];
        return $data;
    }
}
