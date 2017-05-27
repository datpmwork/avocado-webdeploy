<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWebsiteSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('type', 20);
            $table->string('servername');
            $table->boolean('is_on')->default(false);

            $table->string('username');
            $table->string('password');
            $table->string('apache_path');
            $table->string('git_root');
            $table->string('checkout')->default("master");
            $table->string('document_root');

            $table->string('deploy_scripts');

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
        Schema::dropIfExists('menus');
    }
}
