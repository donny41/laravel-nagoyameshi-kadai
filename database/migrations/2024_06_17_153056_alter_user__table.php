<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->after('name', function ($table) {
                $table->string('kana');
            });
            $table->after('password', function ($table) {
                $table->string('postal_code');
                $table->string('address');
                $table->string('phone_number');
                $table->date('birthday')->nullable();
                $table->string('occupation')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('kana');
            $table->dropColumn('postal_code');
            $table->dropColumn('address');
            $table->dropColumn('phone_number');
            $table->dropColumn('birthday');
            $table->dropColumn('occupation');
        });
    }
};
