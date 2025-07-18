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
        Schema::table('users', function ($t){
            $t->enum   ('print_mode',['pdf','direct'])->default('pdf');
            $t->string ('printer_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn('print_mode');
            $t->dropColumn('printer_name');
        });
    }
};
