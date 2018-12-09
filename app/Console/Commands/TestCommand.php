<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\ProjectImportLog;
use App\Services\JiraImportService;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    protected $signature = 'test';

    public function handle()
    {


        $project = Project::where('project_key', 'PE')->first();

        $key = $project->project_key;
        print "Key: {$key}\n";
        $keys = [];
        foreach ($project->issueTypes as $issueType) {

            $fields = json_decode($issueType->fields);
            foreach ($fields as $key => $value) {
                $keys[$key] = $value;
            }
        }
        dump($keys);


    }
}