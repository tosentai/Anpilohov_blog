<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- Переконайтеся, що цей рядок є
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use SoftDeletes;
    use HasFactory; // <-- Переконайтеся, що цей рядок є
    const ROOT = 1;

    protected $fillable // Дозволені для масового призначення атрибути
        = [
            'title',
            'slug',
            'parent_id',
            'description',
        ];

    /**
     * Get the parent category that owns the BlogCategory.
     *
     * Додаємо метод для батьківської категорії
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentCategory()
    {
        // Належить категорії (один-до-багатьох, зворотне)
        // Вказуємо 'parent_id' як foreign key та 'id' як local key
        return $this->belongsTo(BlogCategory::class, 'parent_id', 'id');
    }

    /**
     * Додаємо аксессор для отримання заголовка з відступом для дочірніх категорій
     * @return string
     */
    public function getParentTitleAttribute(): string
    {
        // Якщо батьківська категорія існує, повертаємо її назву.
        // Якщо це коренева категорія (id === ROOT), повертаємо 'Корінь'.
        // В іншому випадку (наприклад, parent_id вказує на неіснуючу категорію), повертаємо '???'.
        $title = $this->parentCategory->title
            ?? ($this->isRoot()
                ? 'Корінь'
                : '???');

        return $title;
    }

    /**
     * Перевірка, чи об'єкт є кореневим.
     *
     * @return bool
     */
    public function isRoot(): bool
    {
        return $this->id === BlogCategory::ROOT;
    }
}
