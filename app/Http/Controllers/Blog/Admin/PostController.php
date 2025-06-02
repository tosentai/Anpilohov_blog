<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Blog\Admin\BaseController;
use App\Repositories\BlogPostRepository;
use App\Repositories\BlogCategoryRepository; // Обов'язково імпортуйте, якщо використовуєте
use App\Http\Requests\BlogPostUpdateRequest;
use App\Http\Requests\BlogPostCreateRequest; // <-- Додайте цей рядок
use App\Models\BlogPost; // <-- Додайте цей рядок

class PostController extends BaseController
{
    /**
     * @var BlogPostRepository
     */
    private $blogPostRepository;

    /**
     * @var BlogCategoryRepository
     */
    private $blogCategoryRepository; // Оголосіть властивість

    /**
     * Відобразити список ресурсів.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $paginator = $this->blogPostRepository->getAllWithPaginate();

        return view('blog.admin.posts.index', compact('paginator'));
    }

    public function __construct()
    {
        parent::__construct();
        $this->blogPostRepository = app(BlogPostRepository::class);
        $this->blogCategoryRepository = app(BlogCategoryRepository::class); // Ініціалізуйте репозиторій
    }

    /**
     * Показати форму для створення нової статті.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $item = new BlogPost(); // Створюємо новий порожній об'єкт статті
        $categoryList = $this->blogCategoryRepository->getForComboBox(); // Отримуємо список категорій для випадаючого списку

        return view('blog.admin.posts.edit', compact('item', 'categoryList'));
    }

    /**
     * Зберегти нову статтю в базі даних.
     *
     * @param  BlogPostCreateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BlogPostCreateRequest $request) // <-- Змінено тип Request
    {
        $data = $request->validated(); // Отримуємо валідовані дані

        // Логіка генерації slug, встановлення published_at та user_id перенесена в Observer
        // Тут цих блоків коду НЕ МАЄ БУТИ (як ми робили в Лабораторній 9)

        $item = BlogPost::create($data); // Створюємо об'єкт і додаємо в БД

        if ($item) {
            return redirect()
                ->route('blog.admin.posts.edit', [$item->id])
                ->with(['success' => 'Успішно створено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Помилка створення'])
                ->withInput();
        }
    }

    /**
     * Оновити існуючу статтю в базі даних.
     *
     * @param  BlogPostUpdateRequest  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BlogPostUpdateRequest $request, string $id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) {
            return back()
                ->withErrors(['msg' => "Запис id=[{$id}] не знайдено"])
                ->withInput();
        }

        $data = $request->validated();

        // Логіка обробки slug та published_at перенесена в BlogPostObserver
        // Тут цих блоків коду вже НЕ МАЄ БУТИ

        $result = $item->update($data);

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
     * Видалити статтю (м'яке видалення).
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        // Soft delete (м'яке видалення): запис залишається в базі даних, але позначається як видалений.
        // Це дозволяє відновити запис пізніше.
        $result = BlogPost::destroy($id);

        // Для повного видалення з БД використовуйте:
        // $result = BlogPost::find($id)->forceDelete();

        if ($result) {
            return redirect()
                ->route('blog.admin.posts.index')
                ->with(['success' => "Запис id[$id] видалено"]);
        } else {
            return back()
                ->withErrors(['msg' => 'Помилка видалення']);
        }
    }

    /**
     * Показати форму для редагування статті.
     *
     * @param  string  $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(string $id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) {
            return back()->withErrors(['msg' => "Запис id=[{$id}] не знайдено"]);
        }

        $categoryList = $this->blogCategoryRepository->getForComboBox();

        return view('blog.admin.posts.edit', compact('item', 'categoryList'));
    }
}
