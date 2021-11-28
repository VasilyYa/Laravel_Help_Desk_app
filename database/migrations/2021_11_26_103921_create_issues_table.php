<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('manager_id')->nullable(); # when a request is created, it doesn't have a manager attached to it
            $table->timestamps();

            $table->softDeletes();
        });

        Schema::table('issues', function (Blueprint $table) {
            $table->foreign('status_id')->references('id')->on('statuses')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->foreign('client_id')->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->foreign('manager_id')->references('id')->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropForeign(['client_id']);
            $table->dropForeign(['status_id']);
        });

        Schema::dropIfExists('issues');
    }
}
