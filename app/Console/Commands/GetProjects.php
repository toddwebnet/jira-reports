<?php

namespace App\Console\Commands;

use App\Services\JiraImportService;
use Illuminate\Console\Command;

class GetProjects extends Command
{
    protected $signature = 'projects:get';

    protected $description = 'get projects';

    public function handle()
    {
        $jira = new JiraImportService();
        $jira->importProjects();

    }
}
