<?php

namespace App\Observers;

use App\Models\BlogPost;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BlogPostObserver
{
    /**
     * Обробка події "creating" (перед створенням запису).
     *
     * @param  BlogPost  $blogPost
     * @return void
     */
    public function creating(BlogPost $blogPost): void
    {
        $this->setPublishedAt($blogPost);
        $this->setSlug($blogPost);
        $this->setHtml($blogPost); // <-- Додайте цей рядок
        $this->setUser($blogPost); // <-- Додайте цей рядок
    }

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
        $this->setHtml($blogPost); // <-- Додайте цей рядок
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

    /**
     * Встановлюємо значення полю content_html з поля content_raw.
     * (Тут можна було б додати перетворення Markdown в HTML).
     *
     * @param BlogPost $blogPost
     * @return void
     */
    protected function setHtml(BlogPost $blogPost): void
    {
        if ($blogPost->isDirty('content_raw')) { // Перевіряємо, чи змінилося поле content_raw
            // Тут можна було б додати логіку для перетворення Markdown в HTML, наприклад:
            // $blogPost->content_html = Parsedown::instance()->text($blogPost->content_raw);
            $blogPost->content_html = $blogPost->content_raw; // Наразі просто копіюємо
        }
    }

    /**
     * Якщо user_id не вказано, то встановимо юзера за замовчуванням (UNKNOWN_USER).
     *
     * @param BlogPost $blogPost
     * @return void
     */
    protected function setUser(BlogPost $blogPost): void
    {
        // Встановлюємо user_id поточного авторизованого користувача,
        // або UNKNOWN_USER, якщо користувач не авторизований.
        $blogPost->user_id = auth()->id() ?? BlogPost::UNKNOWN_USER;
    }
}
