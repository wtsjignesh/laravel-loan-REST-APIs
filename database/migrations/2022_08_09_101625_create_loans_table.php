<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->double('loan_amount');
            $table->integer('loan_term');
            $table->integer('status')->comment('1 - Open, 2 - Closed')->default(1);
            $table->integer('approval_status')->comment('0 - Pending, 1 - Approved, 2 - Rejected')->default(1);
            $table->unsignedBigInteger('modified_user_id')->nullable();
            $table->foreign('modified_user_id')->references('id')->on('users');
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
        Schema::dropIfExists('loans');
    }
};
