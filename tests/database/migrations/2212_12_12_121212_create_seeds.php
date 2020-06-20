<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Yaro\Jarboe\Tests\Models\Model;

class CreateSeeds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (range(1, 5) as $position) {
            Model::create([
                'title' => sprintf('title #%s', $position),
                'description' => sprintf('description #%s', $position),
                'checkbox' => (bool) rand(0, 1),
            ]);
        }

        \DB::table('permissions')->insert([
            'name' => 'existed:list',
            'guard_name' => 'admin',
        ]);
        \DB::table('permissions')->insert([
            'name' => 'existed:delete',
            'guard_name' => 'admin',
        ]);
        \Yaro\Jarboe\Models\Admin::create([
            'name' => 'admin',
            'email' => 'admin@email.com',
            'password' => bcrypt('secret'),
        ]);
        \DB::table('model_has_permissions')->insert([
            'permission_id' => 1,
            'model_type' => 'Yaro\Jarboe\Models\Admin',
            'model_id' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
