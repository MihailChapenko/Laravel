<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortfoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('admin_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('benchmark_id');
            $table->string('name', 200);
            $table->string('parent_name', 200)->nullable();
            $table->text('description');
            $table->string('currency', 3);
            $table->double('allocation_min');
            $table->double('allocation_max');
            $table->integer('sort_order');
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('portfolios');
    }
}
