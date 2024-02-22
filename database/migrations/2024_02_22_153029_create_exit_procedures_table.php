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
        Schema::create('exit_procedures', function (Blueprint $table) {
            $table->id();
            $table->bigIncrements('retirement_id')->nullable();
            $table->bigIncrements('termination_id')->nullable();
            $table->bigIncrements('resignation_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->boolean('exit_interview')->default(false);
            $table->boolean('knowledge_transfer')->default(false);
            $table->boolean('return_of_assets')->default(false);
            $table->boolean('exit_documentation')->default(false);
            $table->boolean('access_deactivation')->default(false);
            $table->boolean('farewell')->default(false);
            $table->boolean('benefits_and_final_payments')->default(false);
            $table->boolean('clearance_process')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exit_procedures');
    }
};
