<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sql = <<<EOT
            CREATE VIEW vw_favorites AS
            SELECT
                favorites.*,
                users.username
            FROM
                favorites
            JOIN
                users ON users.id = favorites.user_id;
        EOT;

        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $sql = <<<EOT
            DROP VIEW IF EXISTS vw_favorites;
        EOT;

        DB::statement($sql);
    }
};
