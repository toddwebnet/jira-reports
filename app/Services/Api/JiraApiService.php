<?php
namespace App\Services\Api;

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
        $sprintFilter = "";
        if($sprint!==null){
            $sprintFilter = "AND Sprint = {$sprint} ";
        }
        $params = [
            'startAt' => $startAt,
            'maxResults' => $maxResults,
            'jql' => "project = {$project} {$sprintFilter}order by created ASC"
        ];
        return $this->call('GET', $endpoint, $params);
    }

}
