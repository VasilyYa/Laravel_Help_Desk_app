<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->timestamps();

            $table->softDeletes();
        });

        Schema::table('requests', function (Blueprint $table) {
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
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropForeign(['client_id']);
            $table->dropForeign(['status_id']);
        });

        Schema::dropIfExists('requests');
    }
}
