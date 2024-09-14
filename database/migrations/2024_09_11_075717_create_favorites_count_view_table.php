<?php declare(strict_types=1);

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
        $sql = <<<EOT
                CREATE VIEW vw_favorites_count AS
                SELECT
                    slug,
                    COUNT(slug) AS count
                FROM
                    favorites
                GROUP BY
                    slug
            EOT;

        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $sql = <<<EOT
                DROP VIEW IF EXISTS vw_favorites_count;
            EOT;

        DB::statement($sql);
    }
};
