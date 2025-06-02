<?php

namespace App\Repositories;

use App\Models\BlogCategory as Model; // Використовуємо аліас Model для App\Models\BlogCategory
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator; // Для підказки типів пагінатора
use Illuminate\Support\Facades\DB; // Для DB::raw у getForComboBox

/**
 * Class BlogCategoryRepository.
 *
 * Репозиторій для роботи з категоріями блогу.
 */
class BlogCategoryRepository extends CoreRepository
{
    /**
     * Повертає назву класу моделі, з якою працює репозиторій.
     *
     * @return string
     */
    protected function getModelClass(): string
    {
        // Абстрагування моделі BlogCategory, для легшого створення іншого репозиторія
        return Model::class;
    }

    /**
     * Отримати модель для редагування в адмінці.
     *
     * @param int $id
     * @return Model
     */
    public function getEdit(int $id): Model
    {
        return $this->startConditions()->find($id);
    }

    /**
     * Отримати список категорій для виводу в випадаючий список.
     *
     * @return Collection
     */
    public function getForComboBox(): Collection // Цей тип повернення вимагає Eloquent Collection
    {
        $columns = implode(', ', [
            'id',
            'CONCAT (id, ". ", title) AS id_title',
        ]);

        $result = $this
            ->startConditions()
            ->selectRaw($columns)
            // ->toBase() // <--- ВИДАЛІТЬ ЦЕЙ РЯДОК!
            ->get();

        return $result;
    }

    /**
     * Отримати категорії для виводу пагінатором.
     *
     * @param int|null $perPage Кількість елементів на сторінці.
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate(?int $perPage = null): LengthAwarePaginator
    {
        $columns = ['id', 'title', 'parent_id', 'created_at', 'updated_at']; // Додаємо created_at, updated_at для відображення

        $result = $this
            ->startConditions()
            ->select($columns)
            ->paginate($perPage);

        return $result;
    }
}
