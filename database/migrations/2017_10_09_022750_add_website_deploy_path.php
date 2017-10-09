<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWebsiteDeployPath extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->string('deploy_path')->after('password')->nullable();
        });

        $websites = \App\Website::all();
        foreach ($websites as $website) {
            $website->deploy_path = $website->document_root;
            if ($website->type == "Laravel") {
                $website->deploy_path = str_replace("/public", "", $website->document_root);
            }
            $website->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn('deploy_path');
        });
    }
}
