<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Yaro\Jarboe\Tests\Models\Model;

class CreateDefaultModelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_model', function ($table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->boolean('checkbox');
            $table->json('meta')->default('[]');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('versionable_model', function ($table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->boolean('checkbox');
            $table->json('meta')->default('[]');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('default_model');
        Schema::drop('versionable_model');
    }
}
