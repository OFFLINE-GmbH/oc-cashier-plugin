<?php namespace OFFLINE\Cashier\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class InstallCashierTables extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_id')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
        });

        Schema::create('offline_cashier_subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name');
            $table->string('stripe_id');
            $table->string('stripe_plan');
            $table->integer('quantity');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('offline_cashier_subscriptions');
        Schema::table('users', function (Blueprint $table) {
            $columns = ['stripe_id', 'card_brand', 'card_last_four', 'trial_ends_at'];
            if (Schema::hasColumns('users', $columns)) {
                $table->dropColumn($columns);
            }
        });
    }
}
