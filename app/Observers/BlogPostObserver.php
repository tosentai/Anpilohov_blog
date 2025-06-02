<?php

namespace App\Observers;

use App\Models\BlogPost;
use Carbon\Carbon; // Додайте цей рядок
use Illuminate\Support\Str; // Додайте цей рядок

class BlogPostObserver
{
    /**
     * Обробка події "updating" (перед оновленням запису).
     *
     * @param  BlogPost  $blogPost
     * @return void
     */
    public function updating(BlogPost $blogPost): void
    {
        $this->setPublishedAt($blogPost);
        $this->setSlug($blogPost);
    }

    /**
     * Обробка події "creating" (перед створенням запису).
     *
     * @param  BlogPost  $blogPost
     * @return void
     */
    public function creating(BlogPost $blogPost): void
    {
        // Встановлюємо user_id при створенні, якщо він не встановлений
        if (empty($blogPost->user_id)) {
            $blogPost->user_id = auth()->id() ?? 1; // Використовуйте auth()->id() для авторизованого користувача, або 1 як дефолт
        }

        $this->setPublishedAt($blogPost);
        $this->setSlug($blogPost);
    }

    /**
     * Якщо поле published_at порожнє і нам прийшло 1 в ключі is_published,
     * то генеруємо поточну дату.
     * Якщо is_published = false, встановлюємо published_at в null.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    protected function setPublishedAt(BlogPost $blogPost): void
    {
        if (empty($blogPost->published_at) && $blogPost->is_published) {
            $blogPost->published_at = Carbon::now();
        } elseif (!$blogPost->is_published) { // Якщо знімаємо публікацію
            $blogPost->published_at = null;
        }
    }

    /**
     * Якщо псевдонім (slug) порожній,
     * то генеруємо псевдонім з заголовка.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    protected function setSlug(BlogPost $blogPost): void
    {
        if (empty($blogPost->slug)) {
            $blogPost->slug = Str::slug($blogPost->title);
        }
    }
}
