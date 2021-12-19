@extends("layouts.base")

@section('title')
    Listado
@endsection
@section('content')
    <input type="hidden" id="url_delete" value="">
    <div class="container mt-4">

        <div>
            @if (isset($languages))
                <x-language :languages="$languages" :languageSelected="$languageSelected" :redirect="'categories.index'" />
            @endif
            <span><a href="{{ route('categories.index') }}" class=""> Categorías</a></span>
        </div>
        <br>
        <h1>Categorías</h1>
        <hr>
        <h2>Listado <a href="{{ route('categories.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i></a></h2>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Categoría</th>
                    <th scope="col" class="text-center">Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <th scope="row">{{ $category['id'] }}</th>
                        <td>{{ $category['slug'] }}</td>
                        <td>{{ $category['name'] }}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button class="btn" style="background:#84b6f4; color:#fff;">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <a class="btn" style="background:#77dd77; color:#fff;" href="{{ route('categories.edit', $category['id']) }}">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <button class="btn" style="background:#ff6961; color:#fff;" onclick="deleteCategory('{{ route('categories.destroy', $category['id']) }}')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div>
            {{ $categories->links() }}
        </div>
    </div>
    <x-buy-tickets />
@endsection

@section('script')
    <script src="{{ asset('/src/js/category/categories.delete.js') }}"></script>
@endsection
