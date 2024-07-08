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
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password');
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->text('quotes')->nullable();
            $table->text('like_quotes')->nullable();
            $table->integer('total_like')->nullable()->default(0);
            $table->integer('total_view')->nullable()->default(0);
            $table->integer('profile_view')->nullable()->default(0);
            $table->integer('followers')->nullable()->default(0);
            $table->integer('following')->nullable()->default(0);
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
