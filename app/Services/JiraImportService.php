<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectAvatar;
use App\Models\ProjectImportLog;
use App\Models\ProjectIssueType;
use App\Models\ProjectIssueTypeField;
use App\Services\Api\JiraApiService;

class JiraImportService
{
    public function importProjects()
    {
        /** @var JiraApiService $jiraApi */
        $jiraApi = app()->make(JiraApiService::class);
        $response = $jiraApi->getProjects();
        $projects = json_decode((string)$response->getBody());

        foreach ($projects as $project) {
            if (in_array($project->key, ['A'])) {
                print "Importing: {$project->key}\n";
                $this->importProject($project->key);
            }
        }
    }

    public function importProject($projectKey)
    {
        /** @var JiraApiService $jiraApi */
        $jiraApi = app()->make(JiraApiService::class);

        $json = (string)$jiraApi->getProject($projectKey)->getBody();
        ProjectImportLog::create([
            'source_key' => 'project',
            'source_id' => $projectKey,
            'source' => $json
        ]);
        $this->processProjectJson($json);
    }

    public function processProjectJson($jsonBlock)
    {
        $probject = json_decode($jsonBlock);
        $projectChunk = $probject->projects[0];
        $project = $this->saveProject($projectChunk);
        $projectId = $project->id;

        $this->saveProjectAvatars($projectId, $projectChunk->avatarUrls);

//        foreach ($projectChunk->issuetypes as $issueType) {
//            $projectIssueType = $this->saveProjectIssueType($projectId, $issueType);
//        }
    }

    private function saveProjectIssueType($projectId, $issueType)
    {
        // dd($issueType->fields);
        $data = [
            'project_id' => $projectId,
            'name' => $issueType->name,
            'icon_url' => $issueType->iconUrl,
            'sub_task' => $issueType->subtask,
            // 'fields' => [],//$this->getProjectIssueTypeFields($issueType->fields),
        ];
        $conditions = [
            'project_id' => $projectId,
            'name' => $issueType->name,
        ];
        return app()->make(ModelService::class)
            ->saveToDb(
                ProjectIssueType::class,
                $conditions,
                $data
            );
    }

    private function saveProject($project)
    {
        $data = [
            'jira_id' => $project->id,
            'project_key' => $project->key,
            'project_name' => $project->name,
        ];
        $condition = [
            'project_key' => $project->key
        ];
        return app()->make(ModelService::class)
            ->saveToDb(
                Project::class,
                $condition,
                $data
            );
    }

    private function saveProjectAvatars($projectId, $avatarUrls)
    {
        ProjectAvatar::where('project_id', $projectId)->delete();
        foreach ($avatarUrls as $size => $url) {
            $data = [
                'project_id' => $projectId,
                'size' => $size,
                'url' => $url,
            ];
            ProjectAvatar::create($data);
        }
    }

    private function getProjectIssueTypeFields($fields)
    {
        dd($fields);
        $returnFields = [];
        foreach ($fields as $field) {
            $returnFields[$field->key] = $field->name;
        }
        return json_encode($returnFields);
    }

}
