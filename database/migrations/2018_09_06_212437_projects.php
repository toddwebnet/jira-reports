<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Projects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('jira_id');
            $table->string('project_key', 16);
            $table->string('project_name', 32);
            $table->timestamps();

            $table->index('project_key');
        });

        Schema::create('project_avatars', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('project_id');
            $table->string('size', 16);
            $table->string('url', 255);
            $table->timestamps();
            $table->foreign('project_id')
                ->references('id')
                ->on('projects');

        });


        Schema::create('project_issue_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('project_id');
            $table->string('name', 32);
            $table->json('fields');
            $table->string('icon_url', 255);
            $table->boolean('sub_task');

            $table->timestamps();
            $table->foreign('project_id')
                ->references('id')
                ->on('projects');
        });

        /*Schema::create('project_issue_types_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('project_issue_type_id');
            $table->string('key', 32);
            $table->string('name', 32);
            $table->timestamps();
            $table->foreign('project_issue_type_id')
                ->references('id')
                ->on('project_issue_types');
        });*/

        Schema::create('project_import_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('source_key', 16);
            $table->string('source_id', 16);
            $table->json('source');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ([
                     'project_import_logs',
                     //'project_issue_types_fields',
                     'project_issue_types',
                     'project_avatars',
                     'projects'
                 ]
                 as $table) {
            Schema::dropIfExists($table);
        }
    }
}
