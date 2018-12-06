<?php

namespace App\Console\Commands;

use App\Services\Api\JiraApiService;
use Illuminate\Console\Command;

class GetTicket extends Command
{

    protected $signature = 'tickets:get {ticket}';

    protected $description = 'GetTicket';

    public function handle()
    {
        $jira = new JiraApiService();
        $response = $jira->getTicket($this->argument('ticket'));
        $this->line('');
        $this->line((string)$response->getBody());
        $this->line('');
        // print_r(json_decode((string)$response->getBody()));

    }
}
