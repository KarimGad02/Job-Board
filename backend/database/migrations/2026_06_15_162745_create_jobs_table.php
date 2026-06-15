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
        Schema::create('jobs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete(); 
        $table->foreignId('category_id')->constrained()->cascadeOnDelete();
        $table->string('title');
        $table->text('description'); 
        $table->text('responsibilities')->nullable(); 
        $table->string('salary_range')->nullable(); 
        $table->text('benefits')->nullable(); 
        $table->string('location')->nullable(); 
        $table->enum('work_type', ['remote', 'onsite', 'hybrid'])->default('onsite'); 
        $table->date('application_deadline')->nullable(); 
        $table->string('company_logo')->nullable();
        $table->enum('status', ['open', 'closed', 'draft'])->default('open');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
