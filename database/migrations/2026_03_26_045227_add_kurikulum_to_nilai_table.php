// database/migrations/xxxx_add_kurikulum_to_nilai_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            if (!Schema::hasColumn('nilai', 'kurikulum')) {
                $table->string('kurikulum', 50)->default('K13')->after('semester');
            }
        });
    }

    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropColumn('kurikulum');
        });
    }
};