<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Repositories\BlogCategoryRepository;

class CategoryController extends BaseController
{
    /**
     * @var BlogCategoryRepository
     */
    private $blogCategoryRepository;

    public function __construct()
    {
        parent::__construct(); // Викликаємо конструктор батьківського класу (BaseController)

        // Ініціалізуємо репозиторій через контейнер сервісів Laravel.
        // app() автоматично вирішить залежності.
        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }
    public function index()
    {
        // $paginator = BlogCategory::paginate(5); // <-- Закоментуйте цей рядок
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(5); // <-- Додайте цей рядок

        return view('blog.admin.categories.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $item = new BlogCategory();
        // $categoryList = BlogCategory::all(); // <-- Закоментуйте цей рядок
        $categoryList = $this->blogCategoryRepository->getForComboBox(); // <-- Додайте цей рядок

        return view('blog.admin.categories.edit', compact('item', 'categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $item = BlogCategory::create($data);

        if ($item) {
            return redirect()
                ->route('blog.admin.categories.edit', [$item->id])
                ->with(['success' => 'Успішно створено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Помилка створення'])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
//        dd(__METHOD__);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id); // <-- Використовуємо репозиторій
        if (empty($item)) {
            abort(404); // Якщо репозиторій не знайде наш ID, кидаємо 404
        }
        $categoryList = $this->blogCategoryRepository->getForComboBox(); // <-- Використовуємо репозиторій
        // Примітка: $item->parent_id не потрібен для getForComboBox, якщо він просто повертає всі категорії.
        // Якщо getForComboBox має логіку виключення поточної категорії або її дочірніх,
        // то $item->id може бути переданий. Але за поточним завданням - не потрібен.

        return view('blog.admin.categories.edit', compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogCategoryUpdateRequest $request, string $id)
    {
        // $item = BlogCategory::find($id); // <-- Замініть цей рядок
        $item = $this->blogCategoryRepository->getEdit($id); // <-- На цей рядок

        if (empty($item)) {
            return back()
                ->withErrors(['msg' => "Запис id=[{$id}] не знайдено"])
                ->withInput();
        }

        $data = $request->validated(); // Отримуємо валідовані дані
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $result = $item->update($data);

        if ($result) {
            return redirect()
                ->route('blog.admin.categories.edit', $item->id)
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
//        dd(__METHOD__);
    }
}
