<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateSocialProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $config = config('social.table_name');

        // Get users table name
        $userModel = new config('auth.providers.users.model');
        $userTable = $userModel->getTable();

        Schema::create($config('user_has_social_provider'), function (Blueprint $table) use ($userTable, $config) {
            $table->integer('user_id')->unsigned();
            $table->integer('social_provider_id')->unsigned();
            $table->string('token');
            $table->string('social_id');
            $table->timestamp('expires_in')->nullable();

            $table->foreign('user_id')
                ->references('id')
                ->on($userTable)
                ->onDelete('cascade');

            $table->foreign('social_provider_id')
                ->references('id')
                ->on($config('social_providers'))
                ->onDelete('cascade');

            $table->primary(['user_id', 'social_provider_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $config = config('social.table_name');

        Schema::dropIfExists($config('user_has_social_provider'));
        Schema::dropIfExists($config('social_providers'));
    }
}
