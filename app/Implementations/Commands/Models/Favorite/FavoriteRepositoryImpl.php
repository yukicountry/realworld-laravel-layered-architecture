<?php declare(strict_types=1);

namespace App\Implementations\Commands\Models\Favorite;

use App\Commands\Models\Favorite\Favorite;
use App\Commands\Models\Favorite\FavoriteRepository;
use Illuminate\Support\Facades\DB;

final class FavoriteRepositoryImpl implements FavoriteRepository
{
    public function save(Favorite $favorite): void
    {
        $dto = $this->mapToDto($favorite);

        DB::table('favorites')->upsert($dto, ['slug', 'user_id']);
    }

    public function delete(string $slug, string $userId): void
    {
        DB::table('favorites')->where('slug', $slug)->where('user_id', $userId)->delete();
    }

    public function deleteFavoritesOfArticle(string $slug): void
    {
        DB::table('favorites')->where('slug', $slug)->delete();
    }

    /**
     * @return array<string, mixed>
     */
    private function mapToDto(Favorite $model): array
    {
        return [
            'slug'    => $model->slug,
            'user_id' => $model->userId,
        ];
    }
}
