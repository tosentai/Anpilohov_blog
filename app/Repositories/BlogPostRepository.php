<?php

namespace App\Repositories;

use App\Models\BlogPost as Model; // Використовуємо аліас Model для App\Models\BlogPost
use Illuminate\Database\Eloquent\Collection; // Можливо, не знадобиться, але лишимо для прикладу
use Illuminate\Contracts\Pagination\LengthAwarePaginator; // Для підказки типів пагінатора

/**
 * Class BlogPostRepository.
 *
 * Репозиторій для роботи зі статтями блогу.
 */
class BlogPostRepository extends CoreRepository
{
    /**
     * Повертає назву класу моделі, з якою працює репозиторій.
     *
     * @return string
     */
    protected function getModelClass(): string
    {
        // Абстрагуємо модель BlogPost
        return Model::class;
    }

    /**
     * Отримати список статей для виводу пагінатором.
     *
     * @param int|null $perPage Кількість елементів на сторінці.
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate(?int $perPage = null): LengthAwarePaginator
    {
        $columns = [
            'id',
            'title',
            'slug',
            'is_published',
            'published_at',
            'user_id',
            'category_id',
            'created_at',
            'updated_at',
        ];

        $result = $this->startConditions()
            ->select($columns)
            ->orderBy('id', 'DESC') // Сортуємо за ID у спадному порядку
            ->paginate($perPage ?? 25); // За замовчуванням 25 статей на сторінку

        return $result;
    }

    /**
     * Отримати модель статті для редагування в адмінці.
     *
     * @param int $id
     * @return Model
     */
    public function getEdit(int $id): Model
    {
        return $this->startConditions()->find($id);
    }
}
