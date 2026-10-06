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
        Schema::create('attendance', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('member_id');
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'excused']);
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('note')->nullable();
            $table->unsignedInteger('marked_by');
            $table->timestamps();

            $table->unique(['member_id', 'date']);
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('marked_by')->references('id')->on('admins');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
