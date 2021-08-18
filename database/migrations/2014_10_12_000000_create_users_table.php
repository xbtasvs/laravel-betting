<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('avatar')->default('img/avatar.png');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('phone')->unique();
            $table->string('address');
            $table->string('city');
            $table->string('zip_code');
            $table->string('country');
            $table->string('state');
            $table->date('birthday');
            $table->integer('odds_pref')->default(1);
            $table->integer('curr_pref')->default(1);
            $table->integer('lang_pref')->default(1);
            $table->float('amount', 8, 2)->default(0);
            $table->string('stripe_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
