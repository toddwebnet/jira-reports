<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Project::where([])->delete();
        \App\Models\Project::create(['project_name' => 'PE']);
        \App\Models\Project::create(['project_name' => 'TRIAGE']);

        \App\Models\Sprint::where([])->delete();
        \App\Models\Sprint::create([
            'sprint_name' => 'GAIA-18',
            'begin_date' => '2018-08-26',
            'end_date' => '2018-09-08'
        ]);
        \App\Models\Sprint::create([
            'sprint_name' => 'GAIA-19',
            'begin_date' => '2018-09-09',
            'end_date' => '2018-09-22'
        ]);

        \App\Models\Status::where([])->delete();

        \App\Models\Status::create([
            'status_name' => 'Open',
            'order_id' => 1,
            'bgcolor' => '#000066',
        ]);
        \App\Models\Status::create([
            'status_name' => 'In Progress',
            'order_id' => 2,
            'bgcolor' => '#006600',
        ]);
        \App\Models\Status::create([
            'status_name' => 'Code Review',
            'order_id' => 3,
            'bgcolor' => '#660066',
        ]);
        \App\Models\Status::create([
            'status_name' => 'In QA',
            'order_id' => 4,
            'bgcolor' => '#ffff33',
        ]);
        \App\Models\Status::create([
            'status_name' => 'Resolved',
            'order_id' => 5,
            'bgcolor' => '#999999',
        ]);
        \App\Models\Status::create([
            'status_name' => 'Closed',
            'order_id' => 6,
            'bgcolor' => '#cccccc',
        ]);
        \App\Models\Status::create([
            'status_name' => 'Waiting for Information',
            'order_id' => 7,
            'bgcolor' => '#ff9999',
        ]);
    }
}
