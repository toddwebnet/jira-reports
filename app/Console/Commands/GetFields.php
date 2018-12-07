<?php

namespace App\Console\Commands;

use App\Services\Api\JiraApiService;
use Illuminate\Console\Command;

class GetFields extends Command
{
    protected $signature = 'tickets:fields {project}';

    protected $description = 'get ticket fields';

    public function handle()
    {
        $jira = new JiraApiService();
        $response = $jira->getFieldLabels($this->argument('project'));
        $this->line('');
        // $this->line((string)$response->getBody());
        dump(json_decode((string)$response->getBody()));
        $this->line('');
        // print_r(json_decode((string)$response->getBody()));

    }
}
