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
        if (! Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->tinyInteger('status')->default(0);
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

                if (Schema::hasColumn('users', 'status')) {
                    Schema::table('users', function (Blueprint $table) {
                        $table->dropColumn('status');
                    });
                }
            });
        }
    }
}
