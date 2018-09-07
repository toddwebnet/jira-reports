<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Sprint;
use App\Services\Api\JiraApiService;
use App\Services\JiraService;
use Illuminate\Console\Command;

class CollectSprintTickets extends Command
{
    protected $signature = 'tickets:collect';

    protected $description = 'CollectSprintTickets';

    public function handle()
    {

        $projects = Project::all();
        $today = date("Y-m-d", time());
        $sprints = Sprint::where('end_date', '>=', $today)
            ->get();

        $jiraService = new JiraService();
        foreach ($projects as $project) {

            if ($project->project_name == 'TRIAGE') {
                $jiraService->collectAndProcessSprintTickets($project, null);
            } else {
                foreach ($sprints as $sprint) {
                    $jiraService->collectAndProcessSprintTickets($project, $sprint);
                }
            }
        }
    }

}
