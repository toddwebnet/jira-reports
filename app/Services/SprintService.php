<?php

namespace App\Services;

use App\Console\Commands\CollectSprintTickets;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\Status;
use Illuminate\Support\Facades\DB;

class SprintService
{
    public function getChartData($projectName, $sprintName = '')
    {
        $project = Project::where('project_name', $projectName)->firstOrFail();

        if ($sprintName == '') {
            $sprint = Sprint::getCurrentSprint();
        } else {
            // sprint
            $sprint = Sprint::where('sprint_name', $sprintName);
        }
        if ($projectName == 'TRIAGE') {
            $statuses = Status::where('status_name', '!=', 'Closed')->orderBy('order_id')->get();
        } else {
            $statuses = Status::where('status_name', '!=', 'Waiting for Information')->orderBy('order_id')->get();
        }

        $params = [$project->id];
        if ($projectName == 'TRIAGE') {
            $sprintJoin = "";
            $sprintCondition = " and status!='Closed' ";
            $params[] = $sprint->begin_date;
            $params[] = $sprint->end_date;
        } else {

            $sprintJoin = "inner join sprints s on s.id = t.sprint_id";
            $sprintCondition = "  and s.id = ? ";
            $params[] = $sprint->id;
        }

        $tickets = DB::select("
        select collection_date, status, sum(coalesce(points, 1)) as points, sum(1) as ticket_count
        from 
        daily_jira_tickets t
        inner join projects p on p.id = t.project_id
        {$sprintJoin}
        where p.id = ? {$sprintCondition}
        and issue_type in ('Story', 'Bug')
        group by collection_date, status
        order by collection_date, status
        ", $params);
        return $this->compileChartData($projectName, $sprint, $this->getSprintWorkDays($sprint, $tickets), $this->buildStatusFields($statuses), $tickets);
    }

    private function compileChartData($projectName, $sprint, $sprintWorkDays, $statusFields, $tickets)
    {

        return [
            'project' => $projectName,
            'title' => "{$projectName} Burn Down Chart for {$sprint->sprint_name} (unpointed tickets default to 1 point}",
            'labels' => array_keys($sprintWorkDays),
            'datasets' => $this->getDataSets($sprintWorkDays, $statusFields, $tickets)
        ];
    }

    private function getSprintWorkDays($sprint, $tickets)
    {
        $days = [
            // 0 => 'Sun',
            1 => 'Mon',
            2 => 'Tue',
            3 => 'Wed',
            4 => 'Thu',
            5 => 'Fri',
            // 6 => 'Sat',
        ];
        $curWeek = 1;
        $movingDate = strtotime($sprint->begin_date);
        $endDate = strtotime($sprint->end_date);

        $sprintWorkDays = [];
        while ($movingDate <= $endDate) {
            $week = date('w', $movingDate);
            if ($week == 1 && count($sprintWorkDays) > 0) {
                $curWeek++;
            }
            if (array_key_exists($week, $days)) {
                $totals = $this->getTotalsByDay($movingDate, $tickets);

                $label = "{$days[$week]}-{$curWeek} (Tickets: {$totals['tickets']} Points: {$totals['points']})";
                $sprintWorkDays[$label] = date("Y-m-d", $movingDate);
            }
            $movingDate = strtotime('+ 1 day', $movingDate);
        }
        return $sprintWorkDays;
    }

    private function getTotalsByDay($timestamp, $tickets)
    {
        $ticketCount = 0;
        $points = 0;
        foreach ($tickets as $ticket) {
            if ($timestamp == strtotime($ticket->collection_date)) {
                $points += $ticket->points;
                $ticketCount += $ticket->ticket_count;
            }
        }

        return [
            'points' => $points,
            'tickets' => $ticketCount,
        ];
    }

    private function buildStatusFields($statuses)
    {
        $statusFields = [];

        foreach ($statuses as $status) {
            $statusFields[$status->status_name] = $status->bgcolor;
        }
        return $statusFields;
    }

    private function getDataSets($sprintWorkDays, $statusFields, $tickets)
    {
        $dataSets = [];
        foreach ($statusFields as $status => $bgColor) {
            $dataSets[] = [
                'label' => $status,
                'backgroundColor' => $this->alphaColor($bgColor, .6),
                'data' => $this->getDataPlots($status, $sprintWorkDays, $tickets),
            ];
        }
        return $dataSets;
    }

    private function alphaColor($color, $alpha)
    {
        if (strpos($color, '#') == 0) {
            return
                'rgb(' .
                hexdec(
                    substr($color, 1, 2)
                ) . ',' .
                hexdec(
                    substr($color, 3, 2)
                ) . ',' .
                hexdec(
                    substr($color, 5, 2)
                ) . ',' .
                $alpha
                . ')';
        }
        return $color;
    }

    private function getDataPlots($status, $sprintWorkDays, $tickets)
    {
        $plots = [];
        foreach (array_values($sprintWorkDays) as $day) {
            $points = 0;
            foreach ($tickets as $ticket) {
                if ($ticket->status == $status
                    && strtotime($day) == strtotime($ticket->collection_date)
                ) {
                    $points += $ticket->points;
                }
            }
            $plots[] = $points;
        }
        return $plots;
    }
}
