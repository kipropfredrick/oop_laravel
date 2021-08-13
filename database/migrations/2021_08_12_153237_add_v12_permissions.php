<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddV12Permissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         DB::table('permissions')->truncate();
        $statement = "INSERT INTO `permissions` (`id`, `parent_id`, `name`, `slug`, `description`) VALUES
(49, 0, 'Users', 'users', 'Access Users Module'),
(50, 49, 'View Users', 'users.view', 'View Users '),
(51, 49, 'Create Users', 'users.create', 'Create users'),
(52, 49, 'Update Users', 'users.update', 'Update Users'),
(53, 49, 'Delete Users', 'users.delete', 'Delete Users'),
(54, 49, 'Manage Roles', 'users.roles', 'Manage user roles')";
        DB::unprepared($statement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
