<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames =  [

            /*
             * When using the "HasRoles" trait from this package, we need to know which
             * table should be used to retrieve your roles. We have chosen a basic
             * default value but you may easily change it to any table you like.
             */

            'roles' => 'roles',

            /*
             * When using the "HasRoles" trait from this package, we need to know which
             * table should be used to retrieve your permissions. We have chosen a basic
             * default value but you may easily change it to any table you like.
             */

            'permissions' => 'permissions',

            /*
             * When using the "HasRoles" trait from this package, we need to know which
             * table should be used to retrieve your models permissions. We have chosen a
             * basic default value but you may easily change it to any table you like.
             */

            'model_has_permissions' => 'model_has_permissions',

            /*
             * When using the "HasRoles" trait from this package, we need to know which
             * table should be used to retrieve your models roles. We have chosen a
             * basic default value but you may easily change it to any table you like.
             */

            'model_has_roles' => 'model_has_roles',

            /*
             * When using the "HasRoles" trait from this package, we need to know which
             * table should be used to retrieve your roles permissions. We have chosen a
             * basic default value but you may easily change it to any table you like.
             */

            'role_has_permissions' => 'role_has_permissions',
        ];
        $columnNames = [

        /*
         * Change this if you want to name the related model primary key other than
         * `model_id`.
         *
         * For example, this would be nice if your primary keys are all UUIDs. In
         * that case, name this `model_uuid`.
         */
        'model_morph_key' => 'model_id',
    ];

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedInteger('permission_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type', ]);

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->primary(
                ['permission_id', $columnNames['model_morph_key'], 'model_type'],
                'model_has_permissions_permission_model_type_primary'
            );
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedInteger('role_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type', ]);

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(
                ['role_id', $columnNames['model_morph_key'], 'model_type'],
                'model_has_roles_role_model_type_primary'
            );
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);

            app('cache')->forget('spatie.permission.cache');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames =  [

            /*
             * When using the "HasRoles" trait from this package, we need to know which
             * table should be used to retrieve your roles. We have chosen a basic
             * default value but you may easily change it to any table you like.
             */

            'roles' => 'roles',

            /*
             * When using the "HasRoles" trait from this package, we need to know which
             * table should be used to retrieve your permissions. We have chosen a basic
             * default value but you may easily change it to any table you like.
             */

            'permissions' => 'permissions',

            /*
             * When using the "HasRoles" trait from this package, we need to know which
             * table should be used to retrieve your models permissions. We have chosen a
             * basic default value but you may easily change it to any table you like.
             */

            'model_has_permissions' => 'model_has_permissions',

            /*
             * When using the "HasRoles" trait from this package, we need to know which
             * table should be used to retrieve your models roles. We have chosen a
             * basic default value but you may easily change it to any table you like.
             */

            'model_has_roles' => 'model_has_roles',

            /*
             * When using the "HasRoles" trait from this package, we need to know which
             * table should be used to retrieve your roles permissions. We have chosen a
             * basic default value but you may easily change it to any table you like.
             */

            'role_has_permissions' => 'role_has_permissions',
        ];

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
}
