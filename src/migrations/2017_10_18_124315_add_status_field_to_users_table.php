<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusFieldToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // If the users table does not already have the status column, we add it
        // if it already has, we don't want to change it. we just set a flag
        // which can be useful during rollback
        if (! Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->tinyInteger('status')->default(0);
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->tinyInteger('lastus')->nullable()->default(1);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'lastus')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('lastus');
            });
        } elseif (Schema::hasColumn('users', 'status')) {
            // if there's no lastus column, we assume we added the status column
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
}
