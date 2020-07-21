<?php

namespace Yaro\Jarboe\Tests\Versionable;

use Yaro\Jarboe\Tests\AbstractBaseTest;

abstract class VersionableTestCase extends AbstractBaseTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->migrateUsersTable();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.providers.users.model', TestVersionableUser::class);
        $app['config']->set('database.default', 'testing');
        $app['config']->set('auth.guards.admin.driver', 'session');
        $app['config']->set('auth.guards.admin.provider', 'admins');
        $app['config']->set('auth.providers.admins.driver', 'eloquent');
        $app['config']->set('auth.providers.admins.model', \Yaro\Jarboe\Models\Admin::class);
        $app['config']->set('permission.models.permission', \Spatie\Permission\Models\Permission::class);
        $app['config']->set('permission.models.role', \Spatie\Permission\Models\Role::class);
        $app['config']->set('permission.cache.expiration_time', 0);
        $app['config']->set('permission.table_names', [
            'roles' => 'roles',
            'permissions' => 'permissions',
            'model_has_permissions' => 'model_has_permissions',
            'model_has_roles' => 'model_has_roles',
            'role_has_permissions' => 'role_has_permissions',
        ]);
        $app['config']->set('permission.column_names', [
            'model_morph_key' => 'model_id',
        ]);
    }

    protected function setUpDatabase()
    {
        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--realpath' => realpath(__DIR__.'/../database/migrations'),
        ]);
    }

    public function migrateUsersTable()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->datetime('last_login')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $this->app['db']->connection()->getSchemaBuilder()->create(ModelWithDynamicVersion::TABLENAME, function ($table) {
            $table->increments('id');
            $table->text('name');
            $table->timestamps();
        });

        $this->app['db']->connection()->getSchemaBuilder()->create(DynamicVersionModel::TABLENAME, function ($table) {
            $table->increments('version_id');
            $table->string('versionable_id');
            $table->string('versionable_type');
            $table->string('user_id')->nullable();
            $table->string('auth_guard')->nullable();
            $table->binary('model_data');
            $table->string('reason', 100)->nullable();
            $table->index('versionable_id');
            $table->timestamps();
        });

        $this->app['db']->connection()->getSchemaBuilder()->create(ModelWithJsonField::TABLENAME, function ($table) {
            $table->increments('id');
            $table->json('json_field');
            $table->timestamps();
        });
    }
}
