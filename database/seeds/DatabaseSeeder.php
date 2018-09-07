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
        \App\Models\Project::create(
            ['project_name' => 'PE']
        );

        \App\Models\Sprint::where([])->delete();
        \App\Models\Sprint::create([
            'sprint_name' => 'GAIA-18',
            'begin_date' => '2018-09-02',
            'end_date' => '2018-09-15'
        ]);
        \App\Models\Sprint::create([
            'sprint_name' => 'GAIA-19',
            'begin_date' => '2018-09-16',
            'end_date' => '2018-09-29'
        ]);

        \App\Models\Status::where([])->delete();

        \App\Models\Status::create([
            'status_name' => 'Open',
            'order_id' => 1,
            'color' => '#ffffff',
            'bgcolor' => '#000033',
        ]);
        \App\Models\Status::create([
            'status_name' => 'In Progress',
            'order_id' => 2,
            'color' => '#ffffff',
            'bgcolor' => '#3333FF',
        ]);
        \App\Models\Status::create([
            'status_name' => 'Code Review',
            'order_id' => 3,
            'color' => '#ffffff',
            'bgcolor' => '#006600',
        ]);
        \App\Models\Status::create([
            'status_name' => 'In QA',
            'order_id' => 4,
            'color' => '#000000',
            'bgcolor' => '#FFFF66',
        ]);
        \App\Models\Status::create([
            'status_name' => 'Resolved',
            'order_id' => 5,
            'color' => '#000000',
            'bgcolor' => '#330000',
        ]);
        \App\Models\Status::create([
            'status_name' => 'Closed',
            'order_id' => 6,
            'color' => '#000000',
            'bgcolor' => '#cccccc',
        ]);
    }
}
