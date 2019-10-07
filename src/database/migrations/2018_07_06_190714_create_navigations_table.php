<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNavigationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_panel_navigation', function (Blueprint $table) {
            // These columns are needed for Baum's Nested Set implementation to work.
            // Column names may be changed, but they *must* all exist and be modified
            // in the model.
            // Take a look at the model scaffold comments for details.
            // We add indexes on parent_id, lft, rgt columns by default.
            $table->increments('id');
            $table->integer('parent_id')->nullable()->index();
            $table->integer('lft')->nullable()->index();
            $table->integer('rgt')->nullable()->index();
            $table->integer('depth')->nullable();

            // Add needed columns here (f.ex: name, slug, path, etc.)
            $table->string('name');
            $table->string('slug');
            $table->string('icon');
            $table->boolean('is_active');

            $table->timestamps();
        });

        $root = new \Yaro\Jarboe\Models\Navigation();
        $root->name = 'Root';
        $root->slug = '';
        $root->is_active = true;
        $root->icon = '';
        $root->save();

        $panelNode = \Yaro\Jarboe\Models\Navigation::create([
            'name' => 'Admin Panel',
            'slug' => 'admin-panel',
            'icon' => 'fa-cogs',
            'is_active' => true,
        ])->makeChildOf($root);

        \Yaro\Jarboe\Models\Navigation::create([
            'name' => 'Admins',
            'slug' => 'admin-panel/admins',
            'icon' => '',
            'is_active' => true,
        ])->makeChildOf($panelNode);
        \Yaro\Jarboe\Models\Navigation::create([
            'name' => 'Roles & Permissions',
            'slug' => 'admin-panel/roles-and-permissions',
            'icon' => '',
            'is_active' => true,
        ])->makeChildOf($panelNode);
        \Yaro\Jarboe\Models\Navigation::create([
            'name' => 'Navigation',
            'slug' => 'admin-panel/navigation',
            'icon' => '',
            'is_active' => true,
        ])->makeChildOf($panelNode);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admin_panel_navigation');
    }
}
