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
            'begin_date'  => '2018-09-02',
            'end_date' => '2018-09-15'
        ]);
        \App\Models\Sprint::create([
            'sprint_name' => 'GAIA-19',
            'begin_date'  => '2018-09-16',
            'end_date' => '2018-09-29'
        ]);

        \App\Models\Status::where([])->delete();
    }
}
