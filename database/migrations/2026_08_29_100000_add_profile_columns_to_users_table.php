<?php

use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('auth.users', function (Blueprint $table) {
            $table->string('role')->default(UserRole::Student->value);
            $table->string('thesis_title')->nullable();
            $table->string('framework')->nullable();
            $table->unsignedSmallInteger('target_minutes')->default(30);
        });
    }

    public function down(): void
    {
        Schema::table('auth.users', function (Blueprint $table) {
            $table->dropColumn(['role', 'thesis_title', 'framework', 'target_minutes']);
        });
    }
};
