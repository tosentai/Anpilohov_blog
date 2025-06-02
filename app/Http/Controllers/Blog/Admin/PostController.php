<?php

namespace App\Http\Controllers\Blog\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Blog\Admin\BaseController;
use App\Repositories\BlogPostRepository;

class PostController extends BaseController // Замініть Controller на BaseController
{
    /**
     * @var BlogPostRepository
     */
    private $blogPostRepository;

    public function __construct()
    {
        parent::__construct(); // Викликаємо конструктор батьківського класу (BaseController)

        // Ініціалізуємо репозиторій через контейнер сервісів Laravel.
        // app() автоматично вирішить залежності.
        $this->blogPostRepository = app(BlogPostRepository::class); // app() повертає об'єкт класу
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
