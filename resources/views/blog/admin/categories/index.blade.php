@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                {{-- Включаємо шаблон для відображення повідомлень про успіх/помилки --}}
                @include('blog.admin.categories.includes.result_messages')

                <nav class="navbar navbar-expand-lg navbar-light bg-light mb-3 rounded-3 px-0"> {{-- Додано px-0 для прибирання горизонтального відступу --}}
                    {{-- Видалено div з класом container-fluid --}}
                    <a href="{{ route('blog.admin.categories.create') }}" class="btn btn-lg btn-primary d-flex align-items-center">
                        <i class="fas fa-plus-circle me-2"></i> Додати категорію
                    </a>
                </nav>

                <div class="card shadow-sm rounded-3">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Список категорій</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Категорія</th>
                                    <th>Батьківська</th>
                                    <th>Дії</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($paginator as $item)
                                    @php /** @var \App\Models\BlogCategory $item */ @endphp
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>
                                            <a href="{{ route('blog.admin.categories.edit', $item->id) }}" class="text-decoration-none fw-bold">
                                                {{ $item->title }}
                                            </a>
                                        </td>
                                        <td @if(in_array($item->parent_id, [0, 1])) class="text-muted" @endif>
                                            {{ $item->parentTitle }}
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                {{-- Кнопка редагування --}}
                                                <a href="{{ route('blog.admin.categories.edit', $item->id) }}" class="btn btn-sm btn-info me-3 shadow-sm rounded d-flex align-items-center" title="Редагувати">
                                                    <i class="fas fa-edit me-1"></i> Редагувати
                                                </a>
                                                {{-- Форма видалення --}}
                                                <form action="{{ route('blog.admin.categories.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Ви впевнені, що хочете видалити цю категорію?')" class="d-inline-block">
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
        @if($paginator->total() > $paginator->count())
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
