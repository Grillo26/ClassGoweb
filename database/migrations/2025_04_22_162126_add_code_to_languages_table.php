<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->string('code', 10)->after('name')->nullable();
        });

        // Actualizar los códigos de idioma existentes
        DB::table('languages')->where('name', 'English')->update(['code' => 'en']);
        DB::table('languages')->where('name', 'Spanish')->update(['code' => 'es']);
        DB::table('languages')->where('name', 'French')->update(['code' => 'fr']);
        DB::table('languages')->where('name', 'German')->update(['code' => 'de']);
        DB::table('languages')->where('name', 'Italian')->update(['code' => 'it']);
        DB::table('languages')->where('name', 'Portuguese')->update(['code' => 'pt']);
        DB::table('languages')->where('name', 'Russian')->update(['code' => 'ru']);
        DB::table('languages')->where('name', 'Chinese')->update(['code' => 'zh']);
        DB::table('languages')->where('name', 'Japanese')->update(['code' => 'ja']);
        DB::table('languages')->where('name', 'Korean')->update(['code' => 'ko']);
        DB::table('languages')->where('name', 'Arabic')->update(['code' => 'ar']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
};
