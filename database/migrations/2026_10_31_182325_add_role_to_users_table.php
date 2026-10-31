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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'teacher', 'student'])->default('student')->after('password');
            $table->string('phone')->nullable()->after('role');
            $table->string('document_id')->nullable()->unique()->after('phone');
            $table->text('address')->nullable()->after('document_id');
            $table->date('birth_date')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('phone');
            $table->dropColumn('document_id');
            $table->dropColumn('address');
            $table->dropColumn('birth_date');

        });
    }
};
