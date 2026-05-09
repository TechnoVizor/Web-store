<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE products ALTER COLUMN image TYPE TEXT');

            return;
        }

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE products MODIFY image TEXT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE products ALTER COLUMN image TYPE VARCHAR(255)');
        } elseif (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE products MODIFY image VARCHAR(255) NULL');
        }
    }
};
