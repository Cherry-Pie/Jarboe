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
            $table->softDeletes();
            $table->timestamps();
        });

        foreach (range(1, 5) as $position) {
            Model::create([
                'title' => sprintf('title #%s', $position),
                'description' => sprintf('description #%s', $position),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('default_model');
    }
}
