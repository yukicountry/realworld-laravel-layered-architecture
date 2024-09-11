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
        $sql = <<<EOT
            CREATE VIEW vw_articles AS
            SELECT
                articles.*,
                vw_favorites_count.count AS favorites_count
            FROM
                articles
            JOIN
                vw_favorites_count ON articles.slug = vw_favorites_count.slug;
        EOT;

        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vw_articles');
    }
};
