<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class James extends Command
{
    protected $signature = 'james';

    protected $description = 'James\'s test stuff';

    public function handle()
    {
        // https://interfolio.atlassian.net/rest/api/2/search?jql=project=PE&sprint=gaia-18
    }
}
