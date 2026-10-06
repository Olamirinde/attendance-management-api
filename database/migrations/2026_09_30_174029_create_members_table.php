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
        Schema::create('members', function ($table) {
        $table->increments('id');
        $table->string('member_code')->unique();
        $table->string('name');
        $table->string('email')->nullable();
        $table->string('phone')->nullable();
        $table->enum('type', ['employee', 'student']);
        $table->string('department_or_class')->nullable();
        $table->enum('status', ['active', 'inactive'])->default('active');
        $table->unsignedInteger('created_by');
        $table->timestamps();

        $table->foreign('created_by')->references('id')->on('admins');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
