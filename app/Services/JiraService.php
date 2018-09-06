<?php
namespace App\Services;

use App\Models\DailyJiraTicket;
use App\Models\Project;
use App\Models\Sprint;
use App\Services\Api\JiraApiService;

class JiraService
{
    public function collectAndProcessSprintTickets(Project $project, Sprint $sprint)
    {

        /** @var  $jira JiraApiService */
        $jira = app()->make(JiraApiService::class);
        $collectionDate = date("Y-m-d", time());
        DailyJiraTicket::where('collection_date', $collectionDate)->delete();
        $page = 0;
        do {
            print "\nPage: {$page}\n";
            $response = $jira->getSprintTickets($project->project_name, $sprint->sprint_name, $page);
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
                        'sprint_id' => $sprint->id,
                        'collection_date' => $collectionDate,
                        'ticket_number' => $issue->key,
                        'issue_type' => $issue->fields->issuetype->name,
                        'status' => $issue->fields->status->name,
                        'assigned_to' => $assignedTo,
                        'priority' => $issue->fields->priority->name,
                        'points' => $issue->fields->customfield_10004,
                    ];
                    DailyJiraTicket::create($colleciton);
                }
            }
            $page++;
        } while ($response->getStatusCode() == 200 && count($daGoods->issues) > 0);
    }
}
