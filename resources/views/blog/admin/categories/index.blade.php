@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @include('blog.admin.categories.includes.result_messages') {{-- Переконайтесь, що цей include є або створіть його --}}

                <nav class="navbar navbar-toggleable-md navbar-light bg-faded mb-3">
                    <a class="btn btn-primary" href="{{ route('blog.admin.categories.create') }}">Додати</a>
                </nav>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover table-striped">
                            <thead>
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
                                        <a href="{{ route('blog.admin.categories.edit', $item->id) }}">
                                            {{ $item->title }}
                                        </a>
                                    </td>
                                    <td @if(in_array($item->parent_id, [0, 1])) class="text-muted" @endif>
                                        {{ $item->parentTitle }} {{-- Тепер використовуємо аксесор для відображення назви --}}
                                    </td>
                                    <td>
                                        {{-- Додайте тут кнопки для редагування/видалення --}}
                                        <a href="{{ route('blog.admin.categories.edit', $item->id) }}" class="btn btn-sm btn-info me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('blog.admin.categories.destroy', $item->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Ви впевнені, що хочете видалити цю категорію?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Пагінація --}}
        @if($paginator->total() > $paginator->count())
            <br>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body d-flex justify-content-center">
                            {{ $paginator->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
