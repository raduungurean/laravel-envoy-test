<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGroupsTable extends Migration
{
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->boolean('private')->unsigned()->index()->default(false);
            $table->string('image')->nullable();
            $table->string('description')->nullable();
            $table->string('short_description')->nullable();
            $table->string('url')->nullable();
        });
    }

    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('private');
            $table->dropColumn('image');
            $table->dropColumn('description');
            $table->dropColumn('short_description');
            $table->dropColumn('url');
        });
    }
}
