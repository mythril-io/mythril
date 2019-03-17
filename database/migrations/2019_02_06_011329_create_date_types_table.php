<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDateTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('format')->unique();
            $table->timestamps();
        });

        DB::table('date_types')->insert([
            ['format' => 'Y-m-d'],
            ['format' => 'Y-m'],
            ['format' => 'Y'],
            ['format' => 'TBD']
            // ['format' => 'YYYYQ1'],
            // ['format' => 'YYYYQ2'],
            // ['format' => 'YYYYQ3'],
            // ['format' => 'YYYYQ4']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('date_types');
    }
}
