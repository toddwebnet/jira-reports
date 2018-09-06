<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DailyJiraTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_jira_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->integer('sprint_id')->unsigned();
            $table->date('collection_date');
            $table->string('ticket_number', 32);
            $table->string('issue_type', 32);
            $table->string('status' , 32);
            $table->string('assigned_to' , 64)->nullable();
            $table->string('priority' , 32);
            $table->decimal('points', 8,2)->nullable();
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
        Schema::dropIfExists('daily_jira_tickets');
    }
}
