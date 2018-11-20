<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeAndDropNameMethodsColumnsIvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invites', function (Blueprint $t) {
           $t->dropColumn('model_name');
           $t->dropColumn('model_method');
           $t->integer('type_id', false, true);
           $t->foreign('type_id')->references('id')->on('invite_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invites', function (Blueprint $t) {
            $t->dropForeign(['type_id']);
            $t->dropColumn('type_id');
            $t->string('model_name')->nullable();
            $t->string('model_method')->nullable();
        });
    }
}
