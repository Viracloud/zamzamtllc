<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->timestamp('token_expires_at')->nullable();
        });
        // Old reset tokens were guessable; drop any that are still pending.
        DB::table('admins')->update(['token' => null]);
    }

    public function down(): void
    {
        Schema::table('admins', fn (Blueprint $table) => $table->dropColumn('token_expires_at'));
    }
};
