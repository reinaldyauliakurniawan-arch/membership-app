<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('isf_id')->nullable()->after('code');
            $table->integer('current_rating')->nullable()->after('isf_id');
            $table->foreignId('division_id')->nullable()->constrained('divisions')->nullOnDelete()->after('current_rating');
            $table->string('nationality')->nullable()->after('division_id');
            $table->enum('skill_level', ['beginner', 'intermediate', 'competitive'])->nullable()->after('nationality');
            $table->boolean('is_coach')->default(false)->after('skill_level');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropColumn(['isf_id', 'current_rating', 'division_id', 'nationality', 'skill_level', 'is_coach']);
        });
    }
};
