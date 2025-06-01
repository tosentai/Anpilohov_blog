<?php

namespace App\Models;

// Обов'язково додайте цей рядок для HasFactory
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Переконайтеся, що цей рядок також є, якщо ви його додавали

class BlogPost extends Model
{
    // Обов'язково додайте цей рядок для використання HasFactory
    use HasFactory;
    use SoftDeletes; // Якщо ви використовуєте SoftDeletes, цей рядок теж повинен бути

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'user_id',
        'slug',
        'title',
        'excerpt',
        'content_raw',
        'content_html',
        'is_published',
        'published_at',
    ];

    // ... інший код моделі, якщо є
}
