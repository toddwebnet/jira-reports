<?php
namespace App\Services\Api;

use App\Models\Sprint;

class JiraApiService extends BaseApi
{
    public function __construct()
    {
        parent::__construct(env('JIRA_HOST'));
        $this->setAuth(env('JIRA_USER'), env('JIRA_PASS'));
    }

    public function getSprintTickets($project, $sprint, $page = 0, $maxResults = 50)
    {
        // https://interfolio.atlassian.net/rest/api/2/search?jql=
        // jql: "project = PE AND Sprint = 284 order by created DESC"
        $endpoint = '/rest/api/2/search';
        $startAt = $page * $maxResults;

        if ($sprint !== null) {
            $sprintFilter = "AND Sprint = {$sprint} ";
        } else {
            $sprintStartDate = Sprint::getCurrentSprintStartDate();
            $sprintFilter = "AND updated >= {$sprintStartDate} ";
        }
        $params = [
            'startAt' => $startAt,
            'maxResults' => $maxResults,
            'jql' => "project = {$project} {$sprintFilter}order by created ASC"
        ];

        return $this->call('GET', $endpoint, $params);
    }

    public function getTicket($ticketNumber)
    {
        $endpoint = '/rest/api/2/issue/' . urlencode($ticketNumber);
        return $this->call('GET', $endpoint);
    }

    public function getProject($project){
        $endpoint = "rest/api/2/issue/createmeta?projectKeys={$project}&expand=projects.issuetypes.fields";
        return $this->call('GET', $endpoint);
    }
    public function getProjects(){
        $endpoint = '/rest/api/2/project';
        return $this->call('GET', $endpoint);
    }

    public function getFieldLabels($project)
    {
        $endpoint = "rest/api/latest/issue/createmeta?&expand=projects.issuetypes.fields";
        print (string)$this->call('GET', $endpoint)->getBody();
        die();
        // $response = json_decode((string)$this->call('GET', $endpoint)->getBody());

        foreach($response->projects[0]->issuetypes[0] as $key => $item){
            // print_r($item);
            print "{$key}\n";


        }


    }

}
