<?php
namespace App\Services;

use App\Models\DailyJiraTicket;
use App\Models\Project;
use App\Models\Sprint;
use App\Services\Api\JiraApiService;

class JiraService
{
    public function collectAndProcessSprintTickets(Project $project, ?Sprint $sprint)
    {

        /** @var  $jira JiraApiService */
        $jira = app()->make(JiraApiService::class);
        $collectionDate = date("Y-m-d", time());

        $deleteFilters = [
            'collection_date' => $collectionDate,
            'project_id' => $project->id
        ];

        if ($sprint === null) {
            $sprintName = null;
            $sprintId = null;
        } else {
            $sprintName = $sprint->sprint_name;
            $sprintId = $sprint->id;
            $deleteFilters['sprint_id'] = $sprint->id;
        }
        DailyJiraTicket::where($deleteFilters)->delete();
        $page = 0;
        print "\n{$project->project_name} - {$sprintName}";
        do {
            print "\nPage: {$page}\n";
            $response = $jira->getSprintTickets($project->project_key, $sprintName, $page);
            if ($response->getStatusCode() == 200) {
                $daGoods = json_decode((string)$response->getBody());
                foreach ($daGoods->issues as $issue) {
                    print ".";
                    try {
                        $assignedTo = $issue->fields->assignee->displayName;
                    } catch (\ErrorException $e) {
                        $assignedTo = null;
                    }
                    $colleciton = [
                        'project_id' => $project->id,
                        'sprint_id' => $sprintId,
                        'collection_date' => $collectionDate,
                        'ticket_number' => $issue->key,
                        'issue_type' => $issue->fields->issuetype->name,
                        'status' => $issue->fields->status->name,
                        'assigned_to' => $assignedTo,
                        'priority' => $issue->fields->priority->name,
                        'points' => $issue->fields->customfield_10002,
                        'epic' => $issue->fields->customfield_10006,
                    ];

                    $ticket = DailyJiraTicket::create($colleciton);
                }
            }
            $page++;
        } while ($response->getStatusCode() == 200 && count($daGoods->issues) > 0);
    }
}
