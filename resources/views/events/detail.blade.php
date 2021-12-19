@extends("layouts.base")

@section('title')
    Detalle de Evento
@endsection
@section('content')
    <div class="container mt-4" id="app-edit-events">
        <div>
            <span><a href="{{ route('events.index') }}" class=""> Eventos</a></span> /
            <span> Detalle</span>
        </div>
        <br>

        <h1>Eventos</h1>
        <hr>
        <h2>Detalle evento</h2>
        <div class="row">
            <div class="col-4 mt-2">
                <label>Slug</label>
                <input name="slug" readonly class="form-control" value="{{ $event['slug'] }}">
            </div>
            <div class="col-4 mt-2">
                <label>Capacidad</label>
                <input type="number" readonly class="form-control" value="{{ $event['capacity'] }}">
            </div>
            <div class="col-4 mt-2">
                <label>Fecha</label>
                <input type="date" readonly name="date" required class="form-control" value="{{ $event['date'] }}">
            </div>
            <div class="col-8 mt-2">
                <label>Categoría</label>
                <input readonly class="form-control" value="{{ $event['category_name'] }}">
            </div>
        </div>
        <br>

        <hr>
        <h2>Descripciones</h2>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="text-center">Idioma</th>
                    <th scope="col" class="text-center">Descripción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($event_descriptions as $description)
                    <tr>
                        <td class="text-center">
                            <span><b>{{ $description['language'] }}</b></span>
                        </td>
                        <td class="text-center">
                            <span>{{ $description['name'] }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
