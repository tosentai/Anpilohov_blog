@extends('layouts.main') {{-- Вказуємо, що цей шаблон розширює layouts.main --}}

@section('content') {{-- Позначаємо секцію, яка буде вставлена в @yield('content') --}}
<div class="container mt-4"> {{-- Додаємо контейнер для центрування та відступів --}}
    <div class="row justify-content-center"> {{-- Додано justify-content-center --}}
        <div class="col-md-12"> {{-- Використовуємо одну повну колонку для обох елементів --}}
            {{-- Включаємо шаблон для відображення повідомлень про успіх/помилки --}}
            @include('blog.admin.posts.includes.result_messages') {{-- Додано include для повідомлень --}}

            <nav class="navbar navbar-expand-lg navbar-light bg-light mb-3 rounded-3 px-0"> {{-- Додано px-0 для прибирання горизонтального відступу --}}
                {{-- Видалено div з класом container-fluid --}}
                <a href="{{ route('blog.admin.posts.create') }}" class="btn btn-lg btn-primary d-flex align-items-center">
                    <i class="fas fa-plus-circle me-2"></i> Додати статтю
                </a>
            </nav>

            <div class="card shadow-sm rounded-3"> {{-- Додано card, shadow-sm, rounded-3 --}}
                <div class="card-header bg-white"> {{-- Додано card-header --}}
                    <h4 class="mb-0">Список постів блогу</h4> {{-- Змінено на h4 та mb-0 --}}
                </div>
                <div class="card-body p-0"> {{-- Додано card-body та p-0 --}}
                    <div class="table-responsive"> {{-- Додано table-responsive --}}
                        <table class="table table-hover table-striped mb-0"> {{-- Додано Bootstrap класи для таблиці --}}
                            <thead class="table-light"> {{-- Додано table-light --}}
                            <tr>
                                <th>#</th> {{-- Змінено ID на # --}}
                                <th>Автор</th> {{-- Додано Автор --}}
                                <th>Категорія</th> {{-- Додано Категорія --}}
                                <th>Заголовок</th>
                                <th>Дата публікації</th> {{-- Змінено на Дата публікації --}}
                                <th>Дії</th> {{-- Додано Дії --}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($paginator as $post) {{-- Змінено $items на $paginator --}}
                            @php /** @var \App\Models\BlogPost $post */ @endphp {{-- Додано тип для $post --}}
                            <tr @if (!$post->is_published) class="table-secondary" @endif> {{-- Додано клас для неопублікованих --}}
                                <td>{{ $post->id }}</td>
                                <td>{{ $post->user->name }}</td> {{-- Виводимо ім'я користувача --}}
                                <td>{{ $post->category->title }}</td> {{-- Виводимо назву категорії --}}
                                <td>
                                    <a href="{{ route('blog.admin.posts.edit', $post->id) }}" class="text-decoration-none fw-bold">{{ $post->title }}</a>
                                </td>
                                <td>
                                    {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d.M H:i') : 'Не опубліковано' }}
                                </td>
                                <td>
                                    <div class="d-flex">
                                        {{-- Кнопка редагування --}}
                                        <a href="{{ route('blog.admin.posts.edit', $post->id) }}" class="btn btn-sm btn-info me-3 shadow-sm rounded d-flex align-items-center" title="Редагувати">
                                            <i class="fas fa-edit me-1"></i> Редагувати
                                        </a>
                                        {{-- Форма видалення --}}
                                        <form action="{{ route('blog.admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Ви впевнені, що хочете видалити цю статтю?')" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger shadow-sm rounded d-flex align-items-center" title="Видалити">
                                                <i class="fas fa-trash-alt me-1"></i> Видалити
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Пагінація --}}
    @if(isset($paginator) && $paginator->total() > $paginator->count()) {{-- Додано isset($paginator) --}}
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm rounded-3">
                <div class="card-body d-flex justify-content-center">
                    {{ $paginator->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
