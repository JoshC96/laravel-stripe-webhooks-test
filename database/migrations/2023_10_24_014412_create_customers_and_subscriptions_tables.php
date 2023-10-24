<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->integer('status')->default(0);

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // plan
            $table->integer('status')->default(0);
            $table->integer('frequency')->default(0);

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('customer_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('set null');

            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_subscriptions', function (Blueprint $table) {
            $table->dropForeign(['subscription_id', 'customer_id']);
            $table->dropColumn(['subscription_id','customer_id']);
        });

        Schema::dropIfExists('customer_subscriptions');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('customers');
    }
};
