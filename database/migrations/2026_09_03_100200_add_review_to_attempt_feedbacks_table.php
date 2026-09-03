<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attempt_feedbacks', function (Blueprint $table) {
            $table->foreignId('reviewer_id')->nullable()->after('attempt_id')->constrained('auth.users')->nullOnDelete();
            $table->unsignedTinyInteger('score')->nullable()->after('kind');
        });
    }

    public function down(): void
    {
        Schema::table('attempt_feedbacks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reviewer_id');
            $table->dropColumn('score');
        });
    }
};
