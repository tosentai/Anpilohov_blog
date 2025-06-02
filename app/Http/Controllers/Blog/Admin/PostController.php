<?php

namespace App\Http\Controllers\Blog\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Blog\Admin\BaseController;
use App\Repositories\BlogPostRepository;
use App\Repositories\BlogCategoryRepository; // Додайте цей рядок
use App\Http\Requests\BlogPostUpdateRequest; // Додайте цей рядок
use Carbon\Carbon; // Додайте цей рядок
use Illuminate\Support\Str; // Додайте цей рядок

class PostController extends BaseController // Замініть Controller на BaseController
{
    /**
     * @var BlogPostRepository
     */
    private $blogPostRepository;
    private $blogCategoryRepository;

    public function __construct()
    {
        parent::__construct(); // Викликаємо конструктор батьківського класу (BaseController)

        // Ініціалізуємо репозиторій через контейнер сервісів Laravel.
        // app() автоматично вирішить залежності.
        $this->blogPostRepository = app(BlogPostRepository::class); // app() повертає об'єкт класу
        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }

    public function index()
    {
        // Отримуємо пагінований список статей за допомогою репозиторію
        $paginator = $this->blogPostRepository->getAllWithPaginate();

        // Передаємо пагінатор у view
        return view('blog.admin.posts.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) // Переконайтеся, що тип аргумента string
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) { // помилка, якщо репозиторій не знайде наш ID
            abort(404);
        }
        $categoryList = $this->blogCategoryRepository->getForComboBox(); // Отримуємо список категорій для випадаючого списку

        return view('blog.admin.posts.edit', compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogPostUpdateRequest $request, string $id) // <-- Змінено тип Request
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) { // якщо ID не знайдено
            return back() // redirect back
            ->withErrors(['msg' => "Запис id=[{$id}] не знайдено"]) // видати помилку
            ->withInput(); // повернути дані
        }

        $data = $request->validated(); // Отримуємо валідовані дані

        // Обробка slug
        if (empty($data['slug'])) { // якщо псевдонім порожній
            $data['slug'] = Str::slug($data['title']); // генеруємо псевдонім з заголовка
        }

        // Обробка is_published та published_at
        // Якщо стаття публікується вперше (published_at порожнє) і is_published прийшло як true
        if (empty($item->published_at) && $data['is_published']) {
            $data['published_at'] = Carbon::now(); // генеруємо поточну дату
        } else if (!$data['is_published']) { // Якщо знімаємо публікацію
            $data['published_at'] = null;
        }

        // Встановлюємо user_id, якщо він не встановлений (для нових статей)
        // Для оновлення, user_id вже має бути встановлений, але можна додати перевірку
        // if (empty($item->user_id)) {
        //     $data['user_id'] = auth()->id(); // Або інший спосіб отримання ID користувача
        // }
        // Примітка: user_id вже має бути встановлений при створенні, або в міграціях,
        // або в сідерах. Для оновлення він не змінюється.

        $result = $item->update($data); // оновлюємо дані об'єкта і зберігаємо в БД

        if ($result) {
            return redirect()
                ->route('blog.admin.posts.edit', $item->id)
                ->with(['success' => 'Успішно збережено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Помилка збереження'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
